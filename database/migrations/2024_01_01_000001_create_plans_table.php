<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->text('short_description')->nullable();
            $table->integer('monthly_price')->nullable(); // Price in dollars
            $table->integer('yearly_price')->nullable(); // Price in dollars
            $table->string('stripe_monthly_price_id')->nullable();
            $table->string('stripe_yearly_price_id')->nullable();
            $table->integer('trial_days')->default(0);
            $table->integer('position')->default(0);
            $table->integer('tier')->default(1);
            $table->boolean('is_popular')->default(false);
            $table->boolean('is_active')->default(true);
            $table->json('features')->nullable(); // Plan features as JSON
            $table->json('limits')->nullable(); // Plan limits as JSON
            $table->json('metadata')->nullable(); // Additional metadata
            $table->timestamps();

            $table->index(['is_active', 'position']);
            $table->index('tier');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
