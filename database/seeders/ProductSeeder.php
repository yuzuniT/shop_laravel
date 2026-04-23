<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->insert([
                        [
                'id' => 'P0001',
                'category_id' => 1, // イヤホン
                'product_name' => 'ダミーイヤホンA',
                'description' => 'これはダミーのイヤホンです。',
                'base_price' => 5980,
                'stock_quantity' => 10,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 'P0002',
                'category_id' => 2, // ヘッドホン
                'product_name' => 'ダミーヘッドホンB',
                'description' => 'これはダミーのヘッドホンです。',
                'base_price' => 12980,
                'stock_quantity' => 5,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
