<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Writeoff;
use App\Models\WriteoffProduct;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\AuthorizationException;

class WiteoffController extends Controller
{
    public function index()
    {
        $writeoffs = Writeoff::all()->sortByDesc('created_at');
        return view('writeoffs.index', compact('writeoffs'));
    }

    public function create()
    {
        $products = Product::all();
        return view('writeoffs.create', compact('products'));
    }

    public function store(Request $request)
    {
        $writeoff = new Writeoff();
        $writeoff->user_id = auth()->user()->id;
        $writeoff->reason = $request->reason;
        $writeoff->writeoff_date = $request->writeoff_date;
        $writeoff->save();

        foreach ($request->products as $product) {
            $writeoffProduct = new WriteoffProduct();
            $writeoffProduct->writeoff_id = $writeoff->id;
            $writeoffProduct->product_id = $product['product_id'];
            $writeoffProduct->quantity = $product['quantity'];
            $writeoffProduct->save();
        }

        return redirect()->route('writeoffs.index')->with('success', 'Списание успешно создано');
    }

    public function edit($id)
    {
        return view('writeoffs.edit', compact('writeoff', 'products'));
    }
    

    public function update(Request $request, $id)
    {
        return view('writeoffs.destroy', compact('id'));
    }

    public function delete($id)
    {
        return view('writeoffs.destroy', compact('id'));
    }
}
