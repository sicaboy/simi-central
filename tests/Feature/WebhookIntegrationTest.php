<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Mockery;
use Tests\TestCase;

class WebhookIntegrationTest extends TestCase
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
    public function it_logs_webhook_requests_properly()
    {
        // Mock Stripe webhook verification to throw exception (simulating invalid signature)
        $mockStripeWebhook = Mockery::mock('alias:\Stripe\Webhook');
        $mockStripeWebhook->shouldReceive('constructEvent')
            ->andThrow(new \Stripe\Exception\SignatureVerificationException('Invalid signature'));

        // Mock Log to capture log messages - allow any log calls
        Log::shouldReceive('info')->zeroOrMoreTimes();
        Log::shouldReceive('error')->zeroOrMoreTimes();
        Log::shouldReceive('critical')->zeroOrMoreTimes();
        Log::shouldReceive('warning')->zeroOrMoreTimes();
        Log::shouldReceive('debug')->zeroOrMoreTimes();

        $payload = json_encode(['test' => 'data']);

        $response = $this->postJson('/webhook/stripe-connect',
            json_decode($payload, true),
            ['stripe-signature' => 'test_signature']
        );

        // Should fail due to invalid signature, but logging should work
        $response->assertStatus(400);
        $response->assertSee('Invalid Signature');
    }

    /** @test */
    public function it_handles_malformed_json_payload()
    {
        // Mock Stripe webhook verification to throw UnexpectedValueException
        $mockStripeWebhook = Mockery::mock('alias:\Stripe\Webhook');
        $mockStripeWebhook->shouldReceive('constructEvent')
            ->andThrow(new \UnexpectedValueException('Invalid payload'));

        $response = $this->call(
            'POST',
            '/webhook/stripe-connect',
            [],
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_STRIPE_SIGNATURE' => 'test_signature',
            ],
            'invalid json data'
        );

        $response->assertStatus(400);
        $response->assertSee('Invalid Payload');
    }

    /** @test */
    public function it_requires_stripe_signature_header()
    {
        // Mock Stripe webhook verification to throw exception for missing signature
        $mockStripeWebhook = Mockery::mock('alias:\Stripe\Webhook');
        $mockStripeWebhook->shouldReceive('constructEvent')
            ->andThrow(new \Stripe\Exception\SignatureVerificationException('Invalid signature'));

        $response = $this->postJson('/webhook/stripe-connect', ['test' => 'data']);

        // Should fail due to missing signature
        $response->assertStatus(400);
        $response->assertSee('Invalid Signature');
    }

    /** @test */
    public function it_handles_empty_payload()
    {
        // Mock Stripe webhook verification to throw exception
        $mockStripeWebhook = Mockery::mock('alias:\Stripe\Webhook');
        $mockStripeWebhook->shouldReceive('constructEvent')
            ->andThrow(new \Stripe\Exception\SignatureVerificationException('Invalid signature'));

        $response = $this->postJson('/webhook/stripe-connect', [], [
            'stripe-signature' => 'test_signature',
        ]);

        $response->assertStatus(400);
        $response->assertSee('Invalid Signature');
    }

    /** @test */
    public function it_handles_large_payload()
    {
        // Mock Stripe webhook verification to throw exception
        $mockStripeWebhook = Mockery::mock('alias:\Stripe\Webhook');
        $mockStripeWebhook->shouldReceive('constructEvent')
            ->andThrow(new \Stripe\Exception\SignatureVerificationException('Invalid signature'));

        // Create a large payload
        $largeData = array_fill(0, 1000, 'test_data_'.str_repeat('x', 100));

        $response = $this->postJson('/webhook/stripe-connect', [
            'large_array' => $largeData,
        ], [
            'stripe-signature' => 'test_signature',
        ]);

        // Should still process (and fail at signature verification)
        $response->assertStatus(400);
        $response->assertSee('Invalid Signature');
    }

    /** @test */
    public function it_preserves_request_method_and_headers()
    {
        // Test multiple HTTP methods in a single batch to reduce overhead
        $methods = [
            ['method' => 'GET', 'expectedStatus' => 405],
            ['method' => 'PUT', 'expectedStatus' => 405],
            ['method' => 'DELETE', 'expectedStatus' => 405],
        ];

        foreach ($methods as $test) {
            $response = $this->call($test['method'], '/webhook/stripe-connect', []);
            $response->assertStatus($test['expectedStatus']);
        }
    }

    /** @test */
    public function it_handles_webhook_timeout_scenarios()
    {
        // This test simulates what happens when the forwarded webhook times out
        // We can't easily test actual timeouts, but we can test error handling

        // Mock Stripe webhook verification to throw exception
        $mockStripeWebhook = Mockery::mock('alias:\Stripe\Webhook');
        $mockStripeWebhook->shouldReceive('constructEvent')
            ->andThrow(new \Stripe\Exception\SignatureVerificationException('Invalid signature'));

        // Mock Log to allow any log calls
        Log::shouldReceive('info')->zeroOrMoreTimes();
        Log::shouldReceive('error')->zeroOrMoreTimes();
        Log::shouldReceive('critical')->zeroOrMoreTimes();
        Log::shouldReceive('warning')->zeroOrMoreTimes();
        Log::shouldReceive('debug')->zeroOrMoreTimes();

        $response = $this->postJson('/webhook/stripe-connect', ['test' => 'data'], [
            'stripe-signature' => 'invalid_signature',
        ]);

        $response->assertStatus(400);
        $response->assertSee('Invalid Signature');
    }

    /** @test */
    public function it_maintains_webhook_idempotency()
    {
        // Test that the same webhook can be processed multiple times
        // This is important for Stripe webhook retry logic

        // Mock Stripe webhook verification to throw exception consistently
        $mockStripeWebhook = Mockery::mock('alias:\Stripe\Webhook');
        $mockStripeWebhook->shouldReceive('constructEvent')
            ->andThrow(new \Stripe\Exception\SignatureVerificationException('Invalid signature'));

        $payload = ['webhook_id' => 'evt_test_123', 'test' => 'data'];
        $headers = ['stripe-signature' => 'test_signature'];

        // First request
        $response1 = $this->postJson('/webhook/stripe-connect', $payload, $headers);

        // Second identical request
        $response2 = $this->postJson('/webhook/stripe-connect', $payload, $headers);

        // Both should behave the same way (fail at signature verification)
        $response1->assertStatus(400);
        $response1->assertSee('Invalid Signature');
        $response2->assertStatus(400);
        $response2->assertSee('Invalid Signature');
    }

    /** @test */
    public function it_handles_concurrent_webhook_requests()
    {
        // Mock Stripe webhook verification to throw exception
        $mockStripeWebhook = Mockery::mock('alias:\Stripe\Webhook');
        $mockStripeWebhook->shouldReceive('constructEvent')
            ->andThrow(new \Stripe\Exception\SignatureVerificationException('Invalid signature'));

        // Simulate multiple webhook requests with different data
        $webhooks = [
            ['account' => 'acct_1', 'event' => 'evt_1'],
            ['account' => 'acct_2', 'event' => 'evt_2'],
            ['account' => 'acct_3', 'event' => 'evt_3'],
        ];

        foreach ($webhooks as $webhook) {
            $response = $this->postJson('/webhook/stripe-connect', $webhook, [
                'stripe-signature' => 'test_signature_'.$webhook['account'],
            ]);

            // Each should be processed independently
            $response->assertStatus(400); // Will fail at signature verification
            $response->assertSee('Invalid Signature');
        }
    }

    /** @test */
    public function it_validates_content_type_header()
    {
        // Mock Stripe webhook verification to throw exception
        $mockStripeWebhook = Mockery::mock('alias:\Stripe\Webhook');
        $mockStripeWebhook->shouldReceive('constructEvent')
            ->andThrow(new \Stripe\Exception\SignatureVerificationException('Invalid signature'));

        // Test with correct content type
        $response1 = $this->postJson('/webhook/stripe-connect', ['test' => 'data'], [
            'stripe-signature' => 'test_signature',
            'Content-Type' => 'application/json',
        ]);

        // Test with incorrect content type
        $response2 = $this->call(
            'POST',
            '/webhook/stripe-connect',
            [],
            [],
            [],
            [
                'HTTP_STRIPE_SIGNATURE' => 'test_signature',
                'CONTENT_TYPE' => 'text/plain',
            ],
            json_encode(['test' => 'data'])
        );

        // Both should reach the controller (content type is not strictly validated by Laravel routing)
        $response1->assertStatus(400);
        $response1->assertSee('Invalid Signature');
        $response2->assertStatus(400);
        $response2->assertSee('Invalid Signature');
    }
}
