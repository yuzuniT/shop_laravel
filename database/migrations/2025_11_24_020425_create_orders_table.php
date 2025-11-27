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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id')->nullable();

            $table->string('familiy_name');
            $table->string('last_name');
            $table->char('postal_code',8);
            $table->string('address');
            $table->string('phone_number',15)->nullable();
            $table->string('email');

            $table->decimal('shipping_fee',10,2)->default(610.00); // 送料は610円で統一する。
            $table->decimal('total_amount',10,2);
            $table->string('payment_method',50);
            $table->string('order_status',20)->default('pending');

            $table->timestamps();
            
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->nullOnDelete(); // ユーザーIDが削除された時、注文テーブル内ではnullとなる
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
