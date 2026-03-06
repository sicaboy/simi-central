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
        Schema::create('module_dependencies', function (Blueprint $table) {
            $table->id();
            $table->string('module_name'); // The module that has dependencies
            $table->string('depends_on'); // The module it depends on
            $table->string('version_constraint'); // Version constraint (^1.0.0, ~1.0.0, >=1.0.0, etc.)
            $table->enum('type', ['required', 'optional'])->default('required');
            $table->json('metadata')->nullable(); // Additional dependency metadata
            $table->timestamps();

            $table->unique(['module_name', 'depends_on'], 'module_dep_unique');
            $table->index(['module_name']);
            $table->index(['depends_on']);
            $table->index(['type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('module_dependencies');
    }
};
