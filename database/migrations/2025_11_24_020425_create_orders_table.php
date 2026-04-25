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
            $table->increments('id'); // int (bigint→int)

            $table->unsignedInteger('user_id')->nullable(); // int (unsignedBigInteger→unsignedInteger)

            $table->string('family_name'); // varchar(255) ✓ 変更なし
            $table->string('last_name');   // varchar(255) ✓ 変更なし
            $table->string('email');       // varchar(255) ✓ 変更なし
            $table->string('phone_number')->nullable(); // varchar(255) (string(15)→string())

            $table->string('contact_type');  // varchar(255) (string(50)→string())
            $table->string('contact_title'); // varchar(255) ✓ 変更なし
            $table->text('message');

            $table->string('status')->default('pending'); // varchar(255) (string(20)→string())

            $table->dateTime('created_at')->nullable(); // timestamps()→datetime個別定義
            $table->dateTime('updated_at')->nullable();

            $table->foreign('user_id', 'contacts_FK_0_0')
                ->references('id')->on('users')
                ->nullOnDelete(); // ユーザーIDが削除されたとき、お問い合わせ情報はnullとなる
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