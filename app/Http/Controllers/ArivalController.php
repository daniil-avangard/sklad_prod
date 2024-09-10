<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Arival;
use App\Models\User;
use App\Models\Product;
use App\Models\ArivalProduct;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\AuthorizationException;
use App\Models\ProductVariant;


class ArivalController extends Controller
{
    public function index()
    {
        $arivals = Arival::all()->sortByDesc('created_at');
        return view('arivals.index', compact('arivals'));
    }

    public function create()
    {
        $products = Product::all();

        return view('arivals.create', compact('products'));
    }

    public function store(Request $request)
    {
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
            $arivalProduct->date_of_actuality = $product['date_of_actuality'];
            $arivalProduct->save();
        }

        return redirect()->route('arivals')->with('success', 'Приход успешно добавлен');
    }

    public function edit($arival)
    {
        return view('arivals.edit', compact('id'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
        ]);
    }

    public function delete($arival)
    {
        return view('arivals.destroy', compact('id'));
    }

    public function show($arival)
    {
        $arival = Arival::find($arival);
        $arivalProducts = ArivalProduct::where('arival_id', $arival->id)->get();
        return view('arivals.show', compact('arival', 'arivalProducts'));
    }

    public function accepted($id)
    {
        $arival = Arival::find($id);
        if (Gate::denies('changeStatus', $arival)) {
            throw new AuthorizationException('У вас нет разрешения на изменение статуса прихода.');
        }

        $arival->status = \App\Enum\ArivalStatusEnum::accepted->value;

        
        foreach ($arival->products as $item) {
            $variant = ProductVariant::where('product_id', $item->product_id)
                                     ->where('date_of_actuality', $item->date_of_actuality)
                                     ->first();
            
            if ($variant) {
                $variant->quantity += $item->quantity;
                $variant->save();
            } else {
                $product = Product::find($item->product_id);
                $sku = $product->sku;
                if ($item->date_of_actuality) {
                    $sku .= '-' . date('dmY', strtotime($item->date_of_actuality));
                }
                $variant = new ProductVariant();
                $variant->product_id = $item->product_id;
                $variant->sku = $sku;
                $variant->quantity = $item->quantity;
                $variant->date_of_actuality = $item->date_of_actuality;
                $variant->save();
            }
        }

        $arival->save();
        return redirect()->route('arivals')->with('success', 'Приход принят');
    }

    public function rejected($id)
    {
        $arival = Arival::find($id);
        if (Gate::denies('changeStatus', $arival)) {
            throw new AuthorizationException('У вас нет разрешения на изменение статуса прихода.');
        }

        $arival->status = \App\Enum\ArivalStatusEnum::rejected->value;
        $arival->save();

        return redirect()->route('arivals')->with('success', 'Приход отклонен');
    }
}
