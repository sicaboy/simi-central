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
        Schema::create('apps', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Booking System, Online Store, Gift Cards, etc.
            $table->string('slug')->unique(); // booking, store, gift-cards, loyalty, feedback, digital-signage
            $table->text('description'); // Detailed description of the app
            $table->string('tagline'); // One-liner description for cards
            $table->string('icon'); // Icon name/class for the app
            $table->string('color', 7)->nullable(); // Primary color for the app
            $table->foreignId('app_category_id')->constrained('app_categories')->onDelete('cascade');
            $table->json('features')->nullable(); // Array of key features
            $table->json('screenshots')->nullable(); // Array of screenshot URLs
            $table->string('version', 20)->default('1.0.0');
            $table->enum('pricing_type', ['free', 'premium', 'addon'])->default('free');
            $table->decimal('price', 10, 2)->nullable(); // Monthly price if not free
            $table->text('help_url')->nullable(); // Link to documentation
            $table->json('dependencies')->nullable(); // Array of app slugs this app depends on
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_core')->default(false); // Core apps that can't be uninstalled
            $table->timestamps();

            $table->index(['is_active', 'sort_order']);
            $table->index(['slug']);
            $table->index(['app_category_id']);
            $table->index(['pricing_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apps');
    }
};
