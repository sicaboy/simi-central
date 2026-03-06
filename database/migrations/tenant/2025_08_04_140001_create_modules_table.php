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
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('version');
            $table->text('description')->nullable();
            $table->string('tagline')->nullable();
            $table->string('icon')->default('cube');
            $table->string('color')->default('#6366f1');
            $table->string('category')->default('Other');
            $table->string('author')->nullable();
            $table->string('license')->nullable();
            $table->json('config')->nullable(); // Store full module.json configuration
            $table->json('compatibility')->nullable(); // PHP/Laravel version requirements
            $table->json('database_config')->nullable(); // Database configuration
            $table->json('routes_config')->nullable(); // Routes configuration
            $table->json('permissions')->nullable(); // Module permissions
            $table->json('features')->nullable(); // Module features list
            $table->string('status')->default('inactive'); // active, inactive, installing, uninstalling
            $table->boolean('is_core')->default(false);
            $table->integer('sort_order')->default(100);
            $table->string('pricing_type')->default('free'); // free, one_time, subscription
            $table->decimal('price', 8, 2)->default(0);
            $table->timestamp('installed_at')->nullable();
            $table->timestamp('activated_at')->nullable();
            $table->timestamp('deactivated_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'is_core']);
            $table->index(['category', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modules');
    }
};
