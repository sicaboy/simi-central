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
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('location_id')->nullable();
            $table->string('name');
            $table->string('slug')->unique()->nullable();
            $table->longText('description')->nullable();
            $table->enum('type', ['room', 'equipment', 'table', 'vehicle', 'facility', 'other'])->default('room');
            $table->integer('quantity')->default(1)->comment('Total quantity available');
            $table->integer('available_quantity')->default(1)->comment('Currently available quantity');
            $table->enum('status', ['active', 'inactive', 'maintenance'])->default('active');
            $table->boolean('is_bookable')->default(true);

            // Capacity and specifications
            $table->integer('capacity')->nullable()->comment('Maximum people/items this resource can accommodate');
            $table->json('specifications')->nullable()->comment('Additional specifications like size, features, etc.');
            $table->decimal('hourly_rate', 10, 2)->nullable()->comment('Hourly rental rate if applicable');

            // Location and identification - area_id replaces location string
            $table->unsignedBigInteger('area_id')->nullable();
            $table->string('identifier')->nullable()->comment('Unique identifier like room number, asset tag');

            // Booking constraints
            $table->integer('min_booking_duration')->default(60)->comment('Minimum booking duration in minutes');
            $table->integer('max_booking_duration')->nullable()->comment('Maximum booking duration in minutes');
            $table->integer('advance_booking_days')->default(30)->comment('How many days in advance can be booked');
            $table->integer('min_notice_hours')->default(2)->comment('Minimum notice required for booking in hours');
            $table->integer('max_cancellation_hours')->default(24)->comment('Maximum hours before booking cancellation is allowed');

            // Setup and cleanup times
            $table->integer('setup_time')->default(0)->comment('Setup time required before use in minutes');
            $table->integer('cleanup_time')->default(0)->comment('Cleanup time required after use in minutes');

            // Media and documentation
            $table->string('image')->nullable();
            $table->json('gallery')->nullable()->comment('Additional images');
            $table->longText('instructions')->nullable()->comment('Usage instructions or special notes');

            // Maintenance tracking
            $table->timestamp('last_maintenance')->nullable();
            $table->timestamp('next_maintenance')->nullable();
            $table->longText('maintenance_notes')->nullable();

            // Timestamps and soft deletes
            $table->timestamps();
            $table->softDeletes();

            // Indexes only (no foreign keys)
            $table->index('area_id');
            $table->index(['status', 'is_bookable']);
            $table->index('type');
            $table->index('available_quantity');
            $table->index('identifier');
            $table->index(['location_id', 'status']);
            $table->index(['area_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resources');
    }
};
