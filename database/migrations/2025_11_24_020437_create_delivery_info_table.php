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
            $table->increments('id'); // int (bigint→int)

            $table->unsignedInteger('user_id'); // int (unsignedBigInteger→unsignedInteger)
            $table->string('postal_code');      // varchar(255) (char(8)→string())
            $table->string('address');          // varchar(255) ✓ 変更なし
            $table->string('phone_number')->nullable(); // varchar(255) (string(15)→string())
            $table->string('email')->nullable();        // varchar(255) ✓ 変更なし
            $table->string('name');                     // varchar(255) ✓ 変更なし

            $table->dateTime('created_at')->nullable(); // timestamps()→datetime個別定義
            $table->dateTime('updated_at')->nullable();

            $table->foreign('user_id', 'delivery_info_FK_0_0')
                ->references('id')->on('users')
                ->cascadeOnDelete(); // ユーザーIDが削除されたとき、配送情報も削除される
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