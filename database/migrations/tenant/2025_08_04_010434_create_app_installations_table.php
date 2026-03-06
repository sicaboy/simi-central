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
        Schema::create('app_installations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('app_id');
            $table->timestamp('installed_at');
            $table->timestamp('uninstalled_at')->nullable();
            $table->json('configuration')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            // Indexes for performance
            $table->index('app_id');
            $table->index(['app_id', 'uninstalled_at'], 'app_active_idx');
            $table->index('installed_at');

            // Unique constraint to prevent duplicate installations
            $table->unique(['app_id', 'uninstalled_at'], 'app_unique_active_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_installations');
    }
};
