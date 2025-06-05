<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Basket;

class ProductListController extends Controller
{
    use AuthorizesRequests;
    private $divisionId;

    public function __construct()
    {
        $this->divisionId = Auth::user()->division_id;
    }

    public function index()
    {
        $this->authorize('create', \App\Models\Order::class);
        $basket = Basket::firstOrCreate(['user_id' => Auth::user()->id]);

        // Продукт находится в группе division_group_product(product_id, division_group_id), division_group_id относится к division_groups(id),
        // есть еще division_division_group(division_group_id, division_id), division_id относится к divisions(id)
        // У пользователя division_id = 7, нужно получить все product_id, которые находятся в division_group_product и в division_division_group(division_id = 7)
        // Пизда, не хотелось ебаться с этим с делать боллее элегантно

        $divisionGroupProducts = DB::table('division_group_product')
            ->join('division_division_group', 'division_group_product.division_group_id', '=', 'division_division_group.division_group_id')
            ->where('division_division_group.division_id', $this->divisionId)
            ->pluck('division_group_product.product_id');

        $productsNew = Product::whereIn('id', $divisionGroupProducts)
            ->orWhereHas('divisions', function ($query) {
                $query->where('division_id', $this->divisionId);
            })->orderBy('name')->get();
            
        $products = array();
        foreach ($productsNew as $product) {
            $variants = $product->variants()->where('is_active', true)->orderBy('date_of_actuality', 'desc')->get();
            $sum = 0;
            foreach ($variants as $vr) {
                $sum += $vr->quantity;
            }
            if ($sum != 0) {
                $products[] = $product;
            }
//            dd($product->id, $variants);
        }
        
        $arrayProductsInBasket = [];
        foreach ($products as $product) {
            $basketProduct = $basket->products()->where('product_id', $product->id)->first();
            if ($basketProduct) {
                $arrayProductsInBasket[$product->id] = $basketProduct->pivot->quantity;
            } else {
                $arrayProductsInBasket[$product->id] = 0;
            }
        }
        

        return view('products.list.index', compact('products', 'arrayProductsInBasket'));
    }

    public function show(Product $product)
    {
        $this->authorize('view', $product);

        $variants = $product->variants()->where('is_active', true)->orderBy('date_of_actuality', 'desc')->get();
        return view('products.list.show', compact('product', 'variants'));
    }
}
