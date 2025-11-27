<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products=Product::with('category')
            ->orderBy('id','desc')
            ->paginate(12); // 1ページあたり12件表示

        return view('products.index', compact('products'));
    }

    public function show(Product $product)
    {
        return view('products.show',compact('product'));
    }
}
