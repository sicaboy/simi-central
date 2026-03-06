<?php

namespace Database\Factories;

use App\Models\Central\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Central\Tenant>
 */
class TenantFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Tenant::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'type' => 'standard',
            'country_code' => $this->faker->countryCode(),
            'timezone' => $this->faker->timezone(),
            'currency' => $this->faker->currencyCode(),
            'status' => 'active',
            'stripe_id' => 'acct_'.Str::random(16),
            'stripe_account_id' => 'acct_'.Str::random(16),
            'tenancy_db_name' => 'tenant_'.Str::random(8),
            'is_ready' => true,
            'trial_ends_at' => now()->addDays(30),
            'billing_country' => $this->faker->countryCode(),
        ];
    }

    /**
     * Indicate that the tenant is not ready.
     */
    public function notReady(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_ready' => false,
        ]);
    }

    /**
     * Indicate that the tenant is on trial.
     */
    public function onTrial(): static
    {
        return $this->state(fn (array $attributes) => [
            'trial_ends_at' => now()->addDays(14),
        ]);
    }

    /**
     * Indicate that the tenant's trial has expired.
     */
    public function trialExpired(): static
    {
        return $this->state(fn (array $attributes) => [
            'trial_ends_at' => now()->subDays(1),
        ]);
    }

    /**
     * Indicate that the tenant has a specific Stripe account ID.
     */
    public function withStripeAccount(string $stripeAccountId): static
    {
        return $this->state(fn (array $attributes) => [
            'stripe_account_id' => $stripeAccountId,
        ]);
    }
}
