<?php

namespace Tests;

use App\Models\Central\Tenant;
use Illuminate\Support\Facades\Http;
use Mockery;
use Sicaboy\SharedSaas\Models\Central\Domain;

/**
 * Test helper methods for common testing operations
 */
class TestHelpers
{
    /**
     * Create a mock Stripe webhook event
     */
    public static function createMockStripeEvent(string $eventType = 'checkout.session.completed', string $accountId = 'acct_test123'): array
    {
        return [
            'id' => 'evt_'.uniqid(),
            'object' => 'event',
            'type' => $eventType,
            'data' => [
                'object' => [
                    'id' => 'cs_'.uniqid(),
                    'object' => 'checkout.session',
                    'amount_total' => 2000,
                    'currency' => 'usd',
                    'customer' => 'cus_'.uniqid(),
                    'payment_status' => 'paid',
                ],
            ],
            'account' => $accountId,
            'api_version' => '2020-08-27',
            'created' => time(),
            'livemode' => false,
            'pending_webhooks' => 1,
            'request' => [
                'id' => 'req_'.uniqid(),
                'idempotency_key' => null,
            ],
        ];
    }

    /**
     * Mock Stripe webhook signature verification
     */
    public static function mockStripeWebhookVerification(array $event, bool $shouldSucceed = true, ?string $exception = null): void
    {
        $mockStripeWebhook = Mockery::mock('alias:\Stripe\Webhook');

        if ($shouldSucceed) {
            $mockStripeWebhook->shouldReceive('constructEvent')
                ->andReturn((object) [
                    'data' => (object) ['object' => (object) $event['data']['object']],
                    'account' => $event['account'],
                    'type' => $event['type'],
                ]);
        } else {
            $exceptionClass = $exception ?? \UnexpectedValueException::class;
            $mockStripeWebhook->shouldReceive('constructEvent')
                ->andThrow(new $exceptionClass('Test exception'));
        }
    }

    /**
     * Mock a tenant with domain (simplified version for better performance)
     */
    public static function mockTenantWithDomain(string $stripeAccountId, string $domainUrl = 'https://test-tenant.example.com'): Tenant
    {
        // Create a real tenant record for testing (more efficient than complex mocking)
        $tenant = Tenant::factory()->create([
            'stripe_account_id' => $stripeAccountId,
            'name' => 'Test Tenant',
            'status' => 'active',
            'is_ready' => true,
        ]);

        // Generate unique domain name using the stripe account ID
        $uniqueDomainName = 'test-'.substr($stripeAccountId, -8);

        // Create a domain for the tenant
        $domain = Domain::create([
            'domain' => $uniqueDomainName,
            'tenant_id' => $tenant->id,
            'is_primary' => true,
            'is_fallback' => false,
        ]);

        // Generate the actual URL that will be used
        $actualUrl = $domain->getFullDomainUrl('/webhook/stripe-connect');

        // Use HTTP mocking with wildcard to match any subdomain
        Http::fake([
            '*'.$uniqueDomainName.'*/webhook/stripe-connect' => Http::response(['status' => 'success'], 200),
            $actualUrl => Http::response(['status' => 'success'], 200),
        ]);

        return $tenant;
    }

    /**
     * Mock tenant not found scenario - just don't create any tenant
     */
    public static function mockTenantNotFound(string $stripeAccountId): void
    {
        // Don't create any tenant - this will result in not found
        // Tests can check for null result from Tenant::where()->first()
    }

    /**
     * Simplified webhook response faker
     */
    public static function fakeWebhookResponse(string $url, array $response = ['status' => 'success'], int $statusCode = 200): void
    {
        Http::fake([
            $url => Http::response($response, $statusCode),
        ]);
    }

    /**
     * Simplified assertion for webhook requests
     */
    public static function assertWebhookRequestSent(string $expectedUrl, ?array $expectedData = null): void
    {
        Http::assertSent(function ($request) use ($expectedUrl, $expectedData) {
            $urlMatches = $request->url() === $expectedUrl;

            $dataMatches = true;
            if ($expectedData !== null) {
                $dataMatches = $request->data() === $expectedData;
            }

            return $urlMatches && $dataMatches;
        });
    }

    /**
     * Create test webhook headers
     */
    public static function createWebhookHeaders(string $signature = 'test_signature'): array
    {
        return [
            'stripe-signature' => $signature,
            'Content-Type' => 'application/json',
            'User-Agent' => 'Stripe/1.0 (+https://stripe.com/docs/webhooks)',
        ];
    }

    /**
     * Generate a valid Stripe signature for testing
     */
    public static function generateStripeSignature(string $payload, string $secret, ?int $timestamp = null): string
    {
        $timestamp = $timestamp ?? time();
        $signedPayload = $timestamp.'.'.$payload;
        $signature = hash_hmac('sha256', $signedPayload, $secret);

        return "t={$timestamp},v1={$signature}";
    }

    /**
     * Create a test tenant with common attributes
     */
    public static function createTestTenant(array $attributes = []): Tenant
    {
        return Tenant::factory()->create(array_merge([
            'name' => 'Test Company',
            'status' => 'active',
            'is_ready' => true,
        ], $attributes));
    }

    /**
     * Assert log was called with specific message
     */
    public static function assertLogCalled(string $level, string $message): void
    {
        \Illuminate\Support\Facades\Log::shouldHaveReceived($level)
            ->with(\Mockery::on(function ($logMessage) use ($message) {
                return str_contains($logMessage, $message);
            }));
    }
}
