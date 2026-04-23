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
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->string('family_name');
            $table->string('last_name');
            $table->string('family_name_kana')->nullable();
            $table->string('last_name_kana')->nullable();

            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            $table->char('postal_code',8)->nullable();
            $table->string('address')->nullable();
            $table->string('phone_number',15)->nullable();

            $table->boolean('is_deleted')->default(false);

            $table->rememberToken(); // Laravel Auth 用

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
