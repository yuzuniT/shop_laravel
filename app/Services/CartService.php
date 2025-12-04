<?php
namespace App\Services;

use App\Models\Product;

class CartService
{
    public function subtotal(array $cart)
    {
        return collect($cart)->sum(
            function($item){
                return $item['price'] * $item['quantity'];
            }
        );

    }

    public function shipping()
    {
        return 610;
    }

    public function total(array $cart)
    {
        return $this->subtotal($cart) + $this->shipping();
    }

}