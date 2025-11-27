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
        Schema::create('delivery_info', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');
            $table->char('postal_code',8);
            $table->string('address');
            $table->string('phone_number',15)->nullable();
            $table->string('email')->nullable();
            $table->string('name');

            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->cascadeOnDelete(); // ユーザーIDが削除されたとき、注文情報も削除される
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_info');
    }
};
