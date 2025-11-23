<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cart = session()->get('cart',[]);
        return view('cart.index', compact('cart'));
    }

    public function empty()
    {
        return view('cart.empty');
    }
}
