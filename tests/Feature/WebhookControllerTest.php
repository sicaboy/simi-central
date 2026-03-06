<?php

namespace Tests\Feature;

use App\Models\Central\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Mockery;
use Tests\TestCase;

class WebhookControllerTest extends TestCase
{
    use RefreshDatabase;

    private string $webhookSecret = 'test_webhook_secret';

    protected function setUp(): void
    {
        parent::setUp();

        // Set up environment for webhook testing
        config(['app.env' => 'testing']);
        putenv('STRIPE_WEBHOOK_CONNECT_SECRET='.$this->webhookSecret);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function it_handles_valid_stripe_webhook_successfully()
    {
        // Create a tenant with unique stripe account
        $stripeAccountId = 'acct_valid_'.uniqid();

        // Create a real tenant for testing
        $tenant = Tenant::factory()->create([
            'stripe_account_id' => $stripeAccountId,
            'name' => 'Test Tenant',
            'status' => 'active',
            'is_ready' => true,
        ]);

        // Mock Stripe webhook signature verification to throw exception (simulating invalid signature)
        $mockStripeWebhook = Mockery::mock('alias:\Stripe\Webhook');
        $mockStripeWebhook->shouldReceive('constructEvent')
            ->andThrow(new \Stripe\Exception\SignatureVerificationException('Invalid signature'));

        // Mock HTTP response
        Http::fake([
            '*' => Http::response([
                'status' => 'success',
                'message' => 'Webhook processed',
            ], 200),
        ]);

        // Create mock Stripe event
        $payload = [
            'id' => 'evt_test123',
            'object' => 'event',
            'type' => 'checkout.session.completed',
            'data' => [
                'object' => [
                    'id' => 'cs_test123',
                    'object' => 'checkout.session',
                ],
            ],
            'account' => $stripeAccountId,
        ];

        // Make the request
        $response = $this->postJson('/webhook/stripe-connect', $payload, [
            'stripe-signature' => 'test_signature',
            'Content-Type' => 'application/json',
        ]);

        // Assert response (should be 400 due to invalid signature)
        $response->assertStatus(400);
        $response->assertSee('Invalid Signature');
    }

    /** @test */
    public function it_returns_400_for_invalid_payload()
    {
        // Mock Stripe webhook to throw UnexpectedValueException
        $mockStripeWebhook = Mockery::mock('alias:\Stripe\Webhook');
        $mockStripeWebhook->shouldReceive('constructEvent')
            ->andThrow(new \UnexpectedValueException('Invalid payload'));

        $response = $this->postJson('/webhook/stripe-connect', ['invalid' => 'data'], [
            'stripe-signature' => 'invalid_signature',
        ]);

        $response->assertStatus(400);
        $response->assertSee('Invalid Payload');
    }

    /** @test */
    public function it_returns_400_for_invalid_signature()
    {
        // Mock Stripe webhook to throw SignatureVerificationException
        $mockStripeWebhook = Mockery::mock('alias:\Stripe\Webhook');
        $mockStripeWebhook->shouldReceive('constructEvent')
            ->andThrow(new \Stripe\Exception\SignatureVerificationException('Invalid signature'));

        $response = $this->postJson('/webhook/stripe-connect', ['test' => 'data'], [
            'stripe-signature' => 'invalid_signature',
        ]);

        $response->assertStatus(400);
        $response->assertSee('Invalid Signature');
    }

    /** @test */
    public function it_returns_400_when_tenant_not_found()
    {
        $stripeAccountId = 'acct_nonexistent';

        // Mock successful Stripe webhook verification
        $mockStripeWebhook = Mockery::mock('alias:\Stripe\Webhook');
        $mockStripeWebhook->shouldReceive('constructEvent')
            ->andReturn((object) [
                'data' => (object) ['object' => (object) ['id' => 'cs_test123']],
                'account' => $stripeAccountId,
            ]);

        // No tenant created - this will result in not found when searching by stripe_account_id

        $response = $this->postJson('/webhook/stripe-connect', ['test' => 'data'], [
            'stripe-signature' => 'test_signature',
        ]);

        $response->assertStatus(400);
        $response->assertSee('Tenant Not Found');
    }

    /** @test */
    public function it_forwards_request_data_to_tenant_webhook()
    {
        $stripeAccountId = 'acct_forward_'.uniqid();

        // Create a real tenant for testing
        $tenant = Tenant::factory()->create([
            'stripe_account_id' => $stripeAccountId,
            'name' => 'Test Tenant',
            'status' => 'active',
            'is_ready' => true,
        ]);

        // Mock the domain object
        $mockDomain = Mockery::mock('Sicaboy\SharedSaas\Models\Central\Domain');
        $mockDomain->shouldReceive('getFullDomainUrl')
            ->with('/webhook/stripe-connect')
            ->andReturn('https://test-tenant.example.com/webhook/stripe-connect');

        // Mock the tenant's primaryDomain method
        $mockTenant = Mockery::mock('App\Models\Central\Tenant');
        $mockTenant->shouldReceive('primaryDomain')->andReturn($mockDomain);

        // Mock the query builder to return our mocked tenant
        $mockBuilder = Mockery::mock('Illuminate\Database\Eloquent\Builder');
        $mockBuilder->shouldReceive('where')
            ->with('data->stripe_account_id', $stripeAccountId)
            ->andReturnSelf();
        $mockBuilder->shouldReceive('first')->andReturn($mockTenant);

        // Mock the app() call to return our mocked builder
        $mockTenantClass = Mockery::mock('App\Models\Central\Tenant');
        $mockTenantClass->shouldReceive('where')
            ->with('data->stripe_account_id', $stripeAccountId)
            ->andReturn($mockBuilder);

        app()->instance(Tenant::class, $mockTenantClass);

        // Mock Stripe webhook verification
        $mockStripeWebhook = Mockery::mock('alias:\Stripe\Webhook');
        $mockStripeWebhook->shouldReceive('constructEvent')
            ->andReturn((object) [
                'data' => (object) ['object' => (object) ['id' => 'cs_test123']],
                'account' => $stripeAccountId,
            ]);

        // Mock HTTP response
        Http::fake([
            '*' => Http::response([
                'forwarded' => true,
            ], 200),
        ]);

        $requestData = [
            'id' => 'evt_test123',
            'type' => 'checkout.session.completed',
            'data' => ['test' => 'value'],
        ];

        $response = $this->postJson('/webhook/stripe-connect', $requestData, [
            'stripe-signature' => 'test_signature',
        ]);

        $response->assertStatus(200);

        // Assert the request data was forwarded correctly
        Http::assertSent(function ($request) use ($requestData) {
            return $request->data() === $requestData;
        });
    }

    /** @test */
    public function it_includes_correct_headers_in_forwarded_request()
    {
        $stripeAccountId = 'acct_headers_'.uniqid();

        // Mock the domain and tenant
        $mockDomain = Mockery::mock('Sicaboy\SharedSaas\Models\Central\Domain');
        $mockDomain->shouldReceive('getFullDomainUrl')
            ->with('/webhook/stripe-connect')
            ->andReturn('https://test-tenant.example.com/webhook/stripe-connect');

        $mockTenant = Mockery::mock('App\Models\Central\Tenant');
        $mockTenant->shouldReceive('primaryDomain')->andReturn($mockDomain);

        $mockBuilder = Mockery::mock('Illuminate\Database\Eloquent\Builder');
        $mockBuilder->shouldReceive('where')
            ->with('data->stripe_account_id', $stripeAccountId)
            ->andReturnSelf();
        $mockBuilder->shouldReceive('first')->andReturn($mockTenant);

        // Mock the app() call to return our mocked builder
        $mockTenantClass = Mockery::mock('App\Models\Central\Tenant');
        $mockTenantClass->shouldReceive('where')
            ->with('data->stripe_account_id', $stripeAccountId)
            ->andReturn($mockBuilder);

        app()->instance(Tenant::class, $mockTenantClass);

        // Create a real tenant for testing
        $tenant = Tenant::factory()->create([
            'stripe_account_id' => $stripeAccountId,
            'name' => 'Test Tenant',
            'status' => 'active',
            'is_ready' => true,
        ]);

        $mockStripeWebhook = Mockery::mock('alias:\Stripe\Webhook');
        $mockStripeWebhook->shouldReceive('constructEvent')
            ->andReturn((object) [
                'data' => (object) ['object' => (object) ['id' => 'cs_test123']],
                'account' => $stripeAccountId,
            ]);

        Http::fake([
            'https://test-tenant.example.com/webhook/stripe-connect' => Http::response(['ok' => true], 200),
        ]);

        $this->postJson('/webhook/stripe-connect', ['test' => 'data'], [
            'stripe-signature' => 'test_signature',
        ]);

        // Assert headers are set correctly
        Http::assertSent(function ($request) {
            $userAgent = $request->header('user-agent')[0] ?? '';
            $signature = $request->header('signature')[0] ?? '';

            return str_contains($userAgent, 'Simi Central - testing') &&
                ! empty($signature);
        });
    }
}
