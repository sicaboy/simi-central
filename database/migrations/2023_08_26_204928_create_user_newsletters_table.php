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
        Schema::create('user_newsletters', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\Sicaboy\SharedSaas\Models\Central\User::class, 'user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->string('email')->unique();
            $table->string('token')->unique();
            $table->boolean('subscribed')->default(false);
            $table->json('categories')->nullable();
            $table->timestamp('subscribed_at')->nullable();
            $table->timestamp('unsubscribed_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->string('language', 5)->default('en');
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
        Schema::dropIfExists('user_newsletters');
    }
};
