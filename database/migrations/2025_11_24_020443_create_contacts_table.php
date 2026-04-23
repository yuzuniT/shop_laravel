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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id')->nullable();

            $table->string('family_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone_number',15)->nullable();

            $table->string('contact_type',50);
            $table->string('contact_title');
            $table->text('message');

            $table->string('status',20)->default('pending');
            
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->nullOnDelete(); // ユーザーIDが削除されたとき、お問い合わせ情報も削除される
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
