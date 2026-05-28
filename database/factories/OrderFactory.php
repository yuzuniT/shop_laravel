<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $base_price   = fake()->numberBetween(500, 10000);
        $quantity     = fake()->numberBetween(1, 5);
        $shipping_fee = 610;
        $subtotal     = $base_price * $quantity;

        return [
            'user_id'        => null, // nullOnDeleteに対応。必要時にUser::factory()で上書き
            'family_name'    => fake('ja_JP')->lastName(),
            'last_name'      => fake('ja_JP')->firstName(),
            'postal_code'    => fake()->numerify('#######'),
            'address'        => fake('ja_JP')->address(),
            'phone_number'   => null,
            'email'          => fake()->safeEmail(),
            'shipping_fee'   => $shipping_fee,
            'total_amount'   => $subtotal + $shipping_fee,
            'payment_method' => fake()->randomElement([
                'credit_card',
                'convenient_store',
                'cash_on_delivery',
                'bank_transfer',
            ]),
            'order_status'   => 'pending',
        ];
    }
}
