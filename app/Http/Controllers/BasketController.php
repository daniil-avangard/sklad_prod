<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Basket;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;


class BasketController extends Controller
{

    private $basket;
    public function __construct()
    {
        $this->basket = Basket::firstOrCreate(['user_id' => Auth::user()->id]);
    }

    public function index()
    {
        // $categories = Category::with(['products' => function ($query) {
        //     $query->whereHas('baskets', function ($query) {
        //         $query->where('basket_id', $this->basket->id);
        //     });
        // }])->get();

        $products = $this->basket->products;

        return view('order.cart.index', compact('products'));
    }

    public function add(Request $request, Product $product)
    {

        $basket = $this->basket;
        $quantity = $request->input('quantity', 1);

        if ($basket->products()->where('product_id', $product->id)->exists()) {
            $basket_product = $basket->products()->where('product_id', $product->id)->first();
            $basket_product->pivot->quantity += $quantity;
            $basket_product->pivot->save();
        } else {
            $basket->products()->attach($product, ['quantity' => $quantity]);
        }

        return redirect()->back()->with('success', 'Добавлено');
    }

    public function remove(Product $product)
    {
        $this->basket->products()->detach($product);

        return redirect()->back()->with('success', 'Удалено');
    }

    public function clear()
    {
        $this->basket->products()->detach();

        return redirect()->back()->with('success', 'Корзина очищена');
    }
}
