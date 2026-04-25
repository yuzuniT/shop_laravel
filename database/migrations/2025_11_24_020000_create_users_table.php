<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id'); // int (bigint→int)

            $table->text('family_name');
            $table->text('last_name');
            $table->text('family_name_kana')->nullable();
            $table->text('last_name_kana')->nullable();

            $table->text('email'); // text型のためuniqueは後述のDB::statementで別途定義
            $table->dateTime('email_verified_at')->nullable(); // timestamp→datetime
            $table->text('password');

            $table->text('postal_code')->nullable();
            $table->text('address')->nullable();
            $table->text('phone_number')->nullable();

            $table->integer('is_deleted')->default(0); // boolean→int (DEFAULT '0')

            $table->text('remember_token')->nullable(); // rememberToken()はvarchar(100)のためtext()に変更

            $table->dateTime('created_at')->nullable(); // timestamps()→datetime個別定義
            $table->dateTime('updated_at')->nullable();
        });

        // text型カラムにはprefixを指定したユニークインデックスが必要
        DB::statement('ALTER TABLE `users` ADD UNIQUE `users_email_unique` (`email`(255))');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};