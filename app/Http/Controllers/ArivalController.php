<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Arival;
use App\Models\Inventory;
use App\Models\User;
use App\Models\Product;
use App\Models\ArivalProduct;


class ArivalController extends Controller
{
    public function index()
    {
        return view('arivals.index');
    }

    public function create()
    {
        $products = Product::all();

        return view('arivals.create', compact('products'));
    }

    public function store(Request $request)
    {
        dd($request->all());
        $arival = new Arival();

        $arival->user_id = auth()->user()->id;
        $arival->invoice = $request->invoice;
        $arival->arrival_date = $request->arrival_date;
        $arival->save();

        foreach ($request->products as $product) {
            $arivalProduct = new ArivalProduct();
            $arivalProduct->arival_id = $arival->id;
            $arivalProduct->product_id = $product['product_id'];
            $arivalProduct->quantity = $product['quantity'];
            $arivalProduct->save();
        }

        dump($arival);
        dump($arivalProduct);
        return dd("Готово");

        $request->validate([
            'name' => 'required',
        ]);




    }

    public function edit($id)
    {
        return view('arivals.edit', compact('id'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
        ]);
    }

    public function destroy($id)
    {
        return view('arivals.destroy', compact('id'));
    }

    public function show($id)
    {
        return view('arivals.show', compact('id'));
    }
}
