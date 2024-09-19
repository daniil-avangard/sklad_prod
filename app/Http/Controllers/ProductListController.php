<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ProductListController extends Controller
{
    public function index()
    {

        $products = Product::whereHas('divisions', function ($query) {
            $query->where('division_id', Auth::user()->division_id);
        })->get();
        return view('products.list.index', compact('products'));
    }

    public function show(Product $product)
    {
        $variants = $product->variants()->where('is_active', true)->orderBy('date_of_actuality', 'desc')->get();
        return view('products.list.show', compact('product', 'variants'));
    }
}
