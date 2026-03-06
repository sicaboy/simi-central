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
        Schema::create('daily_digests', function (Blueprint $table) {
            $table->id();
            $table->date('digest_date')->unique();
            $table->json('data')->comment('Aggregated digest data from all enabled modules');
            $table->json('enabled_modules')->comment('List of module slugs that were enabled when digest was generated');
            $table->timestamps();

            $table->index('digest_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_digests');
    }
};
