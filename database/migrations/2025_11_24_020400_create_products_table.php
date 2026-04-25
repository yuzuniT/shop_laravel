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
        Schema::create('products', function (Blueprint $table) {
            $table->string('id')->primary(); // varchar(255) (string(10)→string())

            $table->unsignedInteger('category_id')->nullable(); // int (unsignedBigInteger→unsignedInteger)

            $table->string('product_name'); // varchar(255) ✓ 変更なし
            $table->text('description')->nullable();
            $table->bigInteger('base_price');              // bigint (decimal→bigInteger、円単位整数管理)
            $table->integer('stock_quantity')->default(0);
            $table->boolean('is_active')->default(true);  // tinyint(1) ✓ 変更なし

            $table->dateTime('created_at')->nullable(); // timestamps()→datetime個別定義
            $table->dateTime('updated_at')->nullable();

            $table->foreign('category_id', 'products_FK_0_0')
                ->references('id')->on('categories')
                ->nullOnDelete(); // カテゴリーIDが削除された時、商品テーブル内ではnullとなる
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};