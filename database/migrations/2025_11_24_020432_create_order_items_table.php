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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('order_id');
            $table->string('product_id',10);

            $table->decimal('price',10,2);
            $table->integer('quantity');
            $table->string('ready_status',20)->default('pending');

            $table->timestamps();

            $table->foreign('order_id')
                ->references('id')->on('orders')
                ->cascadeOnDelete(); // オーダーIDが削除された時、オーダーアイテムも削除される
            
            $table->foreign('product_id')
                ->references('id')->on('products')
                ->restrictOnDelete(); // order_items内に存在する商品の商品IDは削除できない
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
