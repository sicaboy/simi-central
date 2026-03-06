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
        Schema::create('team_plan_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained('teams')->onDelete('cascade');
            $table->foreignId('plan_id')->constrained()->onDelete('cascade');
            $table->string('usage_type'); // e.g., 'users', 'api_calls', 'storage'
            $table->integer('usage_value')->default(0);
            $table->integer('usage_limit')->default(-1); // -1 means unlimited
            $table->timestamp('period_start')->nullable();
            $table->timestamp('period_end')->nullable();
            $table->enum('reset_cycle', ['monthly', 'yearly'])->default('monthly');
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->unique(['team_id', 'plan_id', 'usage_type']);
            $table->index(['team_id', 'usage_type']);
            $table->index(['period_start', 'period_end']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_plan_usages');
    }
};
