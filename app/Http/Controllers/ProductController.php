<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Product\ProductRequest;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(ProductRequest $productRequest)
    {
        $data = $productRequest->only(['name', 'description']);

        $data['user_id'] = Auth::user()->id;

        $product = Product::create($data);

        return redirect()->route('products')->with('success', 'Продукт успешно добавлен');
    }

    public function show(Product $product)
    {
        $divisions = $product->divisions()->get();

        $arivals = $product->arivalProduct()->with('arival')->get()->map(function ($arivalProduct) {
            return [
                'arival' => $arivalProduct->arival,
                'quantity' => $arivalProduct->quantity
            ];
        })->unique('arival.id');

        $writeOffs = $product->writeOffProduct()->with('writeOff')->get()->map(function ($writeOffProduct) {
            return [
                'writeOff' => $writeOffProduct->writeOff,
                'quantity' => $writeOffProduct->quantity
            ];
        })->unique('writeOff.id');
        
        return view('products.show', compact('product', 'divisions', 'arivals', 'writeOffs'));
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(ProductRequest $productRequest, Product $product)
    {
        $data = $productRequest->only(['name', 'description']);

        $product->update($data);

        return redirect()->route('products')->with('success', 'Продукт успешно обновлен');
    }

    public function delete(Product $product)
    {
        $product->delete();

        return redirect()->route('products')->with('success', 'Продукт успешно удален');
    }
}
