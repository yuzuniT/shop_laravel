<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id'=>Str::ulid(), // string型の主キー
            'category_id'=>null,
            'product_name'=>fake()->name(),
            'description'=>fake()->realText(100),
            'base_price'=>fake()->numberBetween(500,10000),
            'stock_quantity'=>fake()->numberBetween(1,100),
            'is_active'=>true,
        ];
    }
}
