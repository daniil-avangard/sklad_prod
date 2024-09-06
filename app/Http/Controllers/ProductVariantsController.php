<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductVariantsController extends Controller
{
    public function index(Product $product)
    {
        
        return view('products.variants.index', compact('product'));
    }

    public function create(Product $product)
    {
        return view('products.variants.create', compact('product'));
    }

    public function store(Product $product, Request $request)
    {
        $product->variants()->create($request->all());
    }
}
