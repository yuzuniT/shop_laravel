<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->input('search');

        $products=Product::query()
            ->search($keyword) // スコープ適用
            ->orderBy('id','desc')
            ->paginate(12) // 1ページあたり12件表示
            ->withQueryString(); // ページネーションに検索条件を保持

        return view('products.index', compact('products','keyword'));
    }

    public function show(Product $product)
    {
        return view('products.show',compact('product'));
    }
}
