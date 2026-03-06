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
        Schema::create('custom_domain_verifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('domain_id');

            $table->enum(
                'status',
                ['pendingDns', 'pendingOwnership', 'pendingValidation', 'active', 'failed']
            )->default('pendingDns');

            $table->string('cloudflare_id')->nullable();
            $table->string('cloudflare_hostname')->nullable();
            $table->string('cloudflare_status')->nullable();

            $table->string('acme_challenge_token')->nullable();
            $table->string('acme_challenge_content')->nullable();
            $table->string('ownership_challenge_token')->nullable();
            $table->string('ownership_challenge_content')->nullable();

            $table->text('dns_record_response')->nullable();
            $table->text('cloudflare_raw_response')->nullable();

            $table->timestamp('verified_at')->nullable()->comment('Cloudflare status is active');
            $table->timestamp('completed_at')->nullable()->comment('URL is actually reachable');
            $table->timestamp('last_synced_at')->nullable()->comment('Last time we checked Cloudflare status');

            $table->timestamps();

            $table->foreign('domain_id')
                ->references('id')
                ->on('domains')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_domain_verifications');
    }
};
