<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 既存の管理者を確認して、存在しなければ作成
        if (!User::where('email', 'admin@example.com')->exists()) {
            User::create([
                'family_name' => '管理',
                'last_name' => '者',
                'family_name_kana' => 'カンリ',
                'last_name_kana' => 'シャ',
                'email' => 'admin@example.com',
                'password' => bcrypt('password123'),
                'postal_code' => '000-0000',
                'address' => 'テスト住所',
                'phone_number' => '09012345678',
                'email_verified_at' => now(),
                'role' => 'admin',
            ]);

            $this->command->info('管理者ユーザーを作成しました。');
            $this->command->info('Email: admin@example.com');
            $this->command->info('Password: password123');
        } else {
            $this->command->info('管理者ユーザーは既に存在します。');
        }
    }
}
