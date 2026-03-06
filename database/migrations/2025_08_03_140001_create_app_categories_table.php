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
        Schema::create('app_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Sales & Commerce, Operations, Customer Relations, Marketing
            $table->string('slug')->unique(); // sales-commerce, operations, customer-relations, marketing
            $table->text('description')->nullable();
            $table->string('icon')->nullable(); // Icon name for the category
            $table->string('color', 7)->nullable(); // Hex color for category theming
            $table->integer('sort_order')->default(0); // For ordering categories
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'sort_order']);
            $table->index(['slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_categories');
    }
};
