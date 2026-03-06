<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiNewsletterTest extends TestCase
{
    use RefreshDatabase;

    public function test_newsletter_subscribe()
    {
        $response = $this->postJson('/api/newsletter/subscribe', [
            'email' => 'unit-test@slj.me',
        ]);
        $response->assertStatus(200);
        // Verify db record
        $this->assertDatabaseHas('user_newsletters', [
            'email' => 'unit-test@slj.me',
        ]);
    }

    public function test_newsletter_subscribe_invalid_email()
    {
        $response = $this->postJson('/api/newsletter/subscribe', [
            'email' => 'unit-test',
        ]);
        $response->assertStatus(422);
        // Verify single error message
        $response->assertJsonValidationErrors('email');
        // no db record
        $this->assertDatabaseMissing('user_newsletters', [
            'email' => 'unit-test',
        ]);
    }

    public function test_newsletter_verify()
    {
        // Load fixture
        $userNewsletter = \Sicaboy\SharedSaas\Models\Central\UserNewsletter::create([
            'email' => 'test@slj.me',
            'token' => '1234567890',
            'subscribed' => false,
        ]);
        $response = $this->getJson('/api/newsletter/verify?token=1234567890');
        $response->assertStatus(200);
        // Verify db record
        $this->assertDatabaseHas('user_newsletters', [
            'email' => 'test@slj.me',
            'token' => '1234567890',
            'subscribed' => true,
        ]);
    }

    public function test_newsletter_verify_invalid_token()
    {
        $response = $this->getJson('/api/newsletter/verify?token=1234567890');
        $response->assertStatus(400);
    }

    public function test_newsletter_unsubscribe()
    {
        // Load fixture
        $userNewsletter = \Sicaboy\SharedSaas\Models\Central\UserNewsletter::create([
            'email' => 'test@slj.me',
            'token' => '1234567890',
            'subscribed' => true,
        ]);

        $response = $this->postJson('/api/newsletter/unsubscribe', [
            'token' => '1234567890',
        ]);
        $response->assertStatus(200);
        // Verify db record
        $this->assertDatabaseHas('user_newsletters', [
            'email' => 'test@slj.me',
            'token' => '1234567890',
            'subscribed' => false,
        ]);
    }

    public function test_newsletter_resubscribe()
    {
        // Load fixture
        $userNewsletter = \Sicaboy\SharedSaas\Models\Central\UserNewsletter::create([
            'email' => 'test@slj.me',
            'token' => '1234567890',
            'subscribed' => false,
        ]);

        $response = $this->postJson('/api/newsletter/resubscribe', [
            'token' => '1234567890',
        ]);
        $response->assertStatus(200);

        // Verify db record
        $this->assertDatabaseHas('user_newsletters', [
            'email' => 'test@slj.me',
            'token' => '1234567890',
            'subscribed' => true,
        ]);
    }
}
