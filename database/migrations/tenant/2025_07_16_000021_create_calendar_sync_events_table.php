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
        Schema::create('calendar_sync_events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('calendar_connection_id');
            $table->string('external_event_id')->index(); // Event ID from external calendar
            $table->unsignedBigInteger('service_booking_id')->nullable();
            $table->string('event_title');
            $table->text('event_description')->nullable();
            $table->timestamp('start_time')->nullable();
            $table->timestamp('end_time')->nullable();
            $table->boolean('all_day')->default(false);
            $table->string('status')->default('confirmed'); // confirmed, cancelled, tentative
            $table->json('external_event_data')->nullable(); // Full event data from external calendar
            $table->string('sync_status')->default('synced'); // synced, pending, error, conflict
            $table->text('sync_error')->nullable();
            $table->timestamp('last_synced_at')->nullable();
            $table->string('conflict_resolution')->nullable(); // manual, auto_local, auto_external
            $table->timestamps();

            $table->index(['calendar_connection_id', 'start_time']);
            $table->index(['service_booking_id']);
            $table->index(['sync_status']);
            $table->index(['start_time', 'end_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calendar_sync_events');
    }
};
