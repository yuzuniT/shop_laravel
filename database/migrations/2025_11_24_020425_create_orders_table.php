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
            $table->increments('id'); // int (SQLite変換元に合わせ bigint→int)

            $table->unsignedInteger('user_id')->nullable(); // int (usersテーブルのidに合わせる)

            $table->text('family_name');
            $table->text('last_name');
            $table->text('postal_code');
            $table->text('address');
            $table->text('phone_number')->nullable();
            $table->text('email');

            $table->bigInteger('shipping_fee')->default(610); // 送料は610円で統一する。整数管理(円単位)
            $table->bigInteger('total_amount');               // 同上
            $table->string('payment_method'); // text→string: text型はDEFAULT値不可のためvarcharに変更
            $table->string('order_status')->default('pending'); // 同上

            $table->dateTime('created_at')->nullable(); // timestampsではなくdatetimeで個別定義
            $table->dateTime('updated_at')->nullable();

            $table->foreign('user_id', 'orders_FK_0_0')
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