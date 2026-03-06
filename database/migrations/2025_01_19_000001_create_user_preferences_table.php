<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Flexible key-value user preferences table
        // Supports both global preferences (tenant_id = null) and tenant-specific settings (tenant_id = specific tenant)
        Schema::create('user_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('tenant_id')->nullable(); // null = global preference, value = tenant-specific setting
            $table->string('preference_key'); // e.g., 'timezone', 'language', 'quick_actions', etc.
            $table->text('preference_value')->nullable(); // stores the actual value
            $table->string('preference_type')->default('string'); // 'string', 'boolean', 'integer', 'array', 'object'
            $table->timestamps();

            $table->unique(['user_id', 'tenant_id', 'preference_key']);
            $table->index('user_id');
            $table->index('tenant_id');
            $table->index(['user_id', 'tenant_id']);
            $table->index('preference_key');
            // Skip preference_value index since it can be large text
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_preferences');
    }
};
