<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Writeoff;
use App\Models\WriteoffProduct;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\AuthorizationException;

class WriteoffController extends Controller
{
    public function index()
    {
        if (Gate::denies('view', Writeoff::class)) {
            throw new AuthorizationException('У вас нет разрешения на просмотр списаний.');
        }
        
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

        return redirect()->route('writeoffs')->with('success', 'Списание успешно создано');
    }

    public function show($writeoff)
    {
        $writeoff = Writeoff::find($writeoff);
        $writeoffProducts = WriteoffProduct::where('writeoff_id', $writeoff->id)->get();
        return view('writeoffs.show', compact('writeoff', 'writeoffProducts'));
    }

    public function edit($writeoff)
    {
        $writeoff = Writeoff::find($writeoff);
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


    public function accepted($id)
    {

        $writeoff = Writeoff::find($id);
        foreach ($writeoff->products as $item) {
            $writeoffProduct = WriteoffProduct::where('product_id', $item->product_id)->first();
            $productItem = Product::where('id', $item->product_id)->first();
            $productItem->quantity -= $writeoffProduct->quantity;
            if ($productItem->quantity < 0) {
                return redirect()->route('writeoffs')->withErrors(['error' => 'Недостаточно товара на складе']);
            }
            $writeoff->status = \App\Enum\WriteoffStatusEnum::accepted->value;

            $productItem->save();
        }

        $writeoff->save();
        return redirect()->route('writeoffs')->with('success', 'Списание принято');
    }

    public function rejected($id)
    {
        $writeoff = Writeoff::find($id);
        $writeoff->status = \App\Enum\WriteoffStatusEnum::rejected->value;
        $writeoff->save();

        return redirect()->route('writeoffs')->with('success', 'Списание отклонено');
    }

}
