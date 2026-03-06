<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_sales', function (Blueprint $table) {
            $table->id();
            $table->string('firstName');
            $table->string('lastName');
            $table->string('jobTitle')->nullable();
            $table->string('employees')->nullable();
            $table->string('company')->nullable();
            $table->string('country')->nullable();
            $table->string('industry')->nullable();
            $table->json('features')->nullable();
            $table->string('otherFeature')->nullable();
            $table->string('phone')->nullable();
            $table->string('email');
            $table->boolean('agreeTerms')->default(false);
            $table->boolean('agreeMarketing')->default(false);
            $table->string('list')->nullable();
            $table->string('token')->unique();
            $table->boolean('processed')->default(false);
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contact_sales');
    }
};
