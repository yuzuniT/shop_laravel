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
            $table->increments('id'); // int (bigint→int)

            $table->unsignedInteger('order_id');      // int (unsignedBigInteger→unsignedInteger)
            $table->string('product_id');             // varchar(255) (string(10)→string())

            $table->bigInteger('price');              // bigint (decimal→bigInteger、円単位整数管理)
            $table->integer('quantity');
            $table->string('ready_status')->default('pending'); // varchar(255) (string(20)→string())

            $table->dateTime('created_at')->nullable(); // timestamps()→datetime個別定義
            $table->dateTime('updated_at')->nullable();

            $table->foreign('product_id', 'order_items_FK_0_0')
                ->references('id')->on('products')
                ->restrictOnDelete(); // order_items内に存在する商品の商品IDは削除できない

            $table->foreign('order_id', 'order_items_FK_1_0')
                ->references('id')->on('orders')
                ->cascadeOnDelete(); // オーダーIDが削除された時、オーダーアイテムも削除される
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