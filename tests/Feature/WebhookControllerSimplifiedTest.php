<?php

namespace Tests\Feature;

use App\Models\Central\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Mockery;
use Sicaboy\SharedSaas\Models\Central\Domain;
use Tests\TestCase;
use Tests\TestHelpers;

class WebhookControllerSimplifiedTest extends TestCase
{
    use RefreshDatabase;

    private string $webhookSecret = 'test_webhook_secret';

    protected function setUp(): void
    {
        parent::setUp();

        config(['app.env' => 'testing']);
        putenv('STRIPE_WEBHOOK_CONNECT_SECRET='.$this->webhookSecret);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function it_processes_valid_webhook_successfully()
    {
        // Generate unique stripe account ID for this test
        $stripeAccountId = 'acct_test_'.uniqid();

        // Arrange
        $event = TestHelpers::createMockStripeEvent('checkout.session.completed', $stripeAccountId);

        TestHelpers::mockStripeWebhookVerification($event);
        $tenant = TestHelpers::mockTenantWithDomain($stripeAccountId);

        // Act
        $response = $this->postJson('/webhook/stripe-connect', $event,
            TestHelpers::createWebhookHeaders()
        );

        // Assert
        $response->assertStatus(200);
        $response->assertJson(['status' => 'success']);

        // Assert that a webhook request was sent to the tenant's domain
        $expectedUrl = $tenant->primaryDomain()->getFullDomainUrl('/webhook/stripe-connect');
        TestHelpers::assertWebhookRequestSent($expectedUrl);
    }

    /** @test */
    public function it_handles_invalid_payload_gracefully()
    {
        // Arrange
        $event = TestHelpers::createMockStripeEvent();
        TestHelpers::mockStripeWebhookVerification($event, false, \UnexpectedValueException::class);

        // Act
        $response = $this->postJson('/webhook/stripe-connect', $event,
            TestHelpers::createWebhookHeaders()
        );

        // Assert
        $response->assertStatus(400);
        $response->assertSee('Invalid Payload');
    }

    /** @test */
    public function it_handles_invalid_signature_gracefully()
    {
        // Arrange
        $event = TestHelpers::createMockStripeEvent();
        TestHelpers::mockStripeWebhookVerification($event, false, \Stripe\Exception\SignatureVerificationException::class);

        // Act
        $response = $this->postJson('/webhook/stripe-connect', $event,
            TestHelpers::createWebhookHeaders()
        );

        // Assert
        $response->assertStatus(400);
        $response->assertSee('Invalid Signature');
    }

    /** @test */
    public function it_handles_tenant_not_found_gracefully()
    {
        // Arrange
        $event = TestHelpers::createMockStripeEvent('checkout.session.completed', 'acct_nonexistent_'.uniqid());

        TestHelpers::mockStripeWebhookVerification($event);
        TestHelpers::mockTenantNotFound('acct_nonexistent');

        // Act
        $response = $this->postJson('/webhook/stripe-connect', $event,
            TestHelpers::createWebhookHeaders()
        );

        // Assert
        $response->assertStatus(400);
        $response->assertSee('Tenant Not Found');
    }

    /** @test */
    public function it_forwards_all_request_data_to_tenant()
    {
        // Generate unique stripe account ID for this test
        $stripeAccountId = 'acct_forward_'.uniqid();

        // Arrange
        $event = TestHelpers::createMockStripeEvent('invoice.payment_succeeded', $stripeAccountId);
        $event['custom_field'] = 'custom_value'; // Add custom data

        TestHelpers::mockStripeWebhookVerification($event);
        $tenant = TestHelpers::mockTenantWithDomain($stripeAccountId);

        // Act
        $response = $this->postJson('/webhook/stripe-connect', $event,
            TestHelpers::createWebhookHeaders()
        );

        // Assert
        $response->assertStatus(200);

        // Assert that the request was sent to the correct URL with the correct data
        $expectedUrl = $tenant->primaryDomain()->getFullDomainUrl('/webhook/stripe-connect');
        TestHelpers::assertWebhookRequestSent($expectedUrl, $event);
    }

    /** @test */
    public function it_handles_different_stripe_event_types()
    {
        // Use a single test with data provider approach instead of loop
        // This reduces the overhead of multiple mock setups
        $eventTypes = [
            'checkout.session.completed',
            'invoice.payment_succeeded',
            'customer.subscription.created',
            'payment_intent.succeeded',
        ];

        // Set up a global HTTP fake that responds to all webhook URLs
        Http::fake([
            '*/webhook/stripe-connect' => Http::response(['status' => 'success'], 200),
        ]);

        $successCount = 0;
        $tenants = [];

        foreach ($eventTypes as $index => $eventType) {
            // Use unique stripe account ID for each iteration
            $stripeAccountId = 'acct_event_'.$index.'_'.uniqid();

            // Create tenant with domain but don't set up individual HTTP fakes
            $tenant = Tenant::factory()->create([
                'stripe_account_id' => $stripeAccountId,
                'name' => 'Test Tenant',
                'status' => 'active',
                'is_ready' => true,
            ]);

            // Generate unique domain name
            $uniqueDomainName = 'test-'.substr($stripeAccountId, -8);
            Domain::create([
                'domain' => $uniqueDomainName,
                'tenant_id' => $tenant->id,
                'is_primary' => true,
                'is_fallback' => false,
            ]);

            $tenants[] = $tenant;

            // Arrange - simplified mock setup
            $event = TestHelpers::createMockStripeEvent($eventType, $stripeAccountId);
            TestHelpers::mockStripeWebhookVerification($event);

            // Act
            $response = $this->postJson('/webhook/stripe-connect', $event,
                TestHelpers::createWebhookHeaders()
            );

            // Assert
            if ($response->status() === 200) {
                $successCount++;
            } else {
                $this->fail("Event type {$eventType} failed with status {$response->status()}");
            }
        }

        // Verify all events were processed successfully
        $this->assertEquals(4, $successCount, "Expected 4 successful responses, got {$successCount}");

        // Verify all requests were made
        Http::assertSentCount(4);
    }

    /** @test */
    public function it_includes_correct_environment_in_user_agent()
    {
        // Test only one environment to simplify the test
        $env = 'testing';
        config(['app.env' => $env]);

        // Use unique stripe account ID
        $stripeAccountId = 'acct_env_'.uniqid();

        // Arrange
        $event = TestHelpers::createMockStripeEvent('checkout.session.completed', $stripeAccountId);

        TestHelpers::mockStripeWebhookVerification($event);
        $tenant = TestHelpers::mockTenantWithDomain($stripeAccountId);

        // Act
        $response = $this->postJson('/webhook/stripe-connect', $event,
            TestHelpers::createWebhookHeaders()
        );

        // Assert that request was made with correct user agent
        Http::assertSent(function ($request) use ($env) {
            $userAgent = $request->header('user-agent')[0] ?? '';

            return str_contains($userAgent, "Simi Central - {$env}");
        });

        // Also verify the response was successful
        $response->assertStatus(200);
    }
}
