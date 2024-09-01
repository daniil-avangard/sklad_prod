<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        // Тут выводит только название продукта и количество
        $inventories = Inventory::with('product')->get()->map(function ($inventory) {
            return [
                'product' => $inventory->product->name,
                'quantity' => $inventory->quantity
            ];
        });
        return view('inventories.index', compact('inventories'));
    }

    public function create()
    {
        return view('inventories.create');
    }

    public function store(Request $request)
    {
        $inventory = Inventory::create($request->all());
        return redirect()->route('inventories.index');
    }

    public function show(Inventory $inventory)
    {
        return view('inventories.show', compact('inventory'));
    }
}
