<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Division;
use App\Http\Requests\Division\CreateDisionRequest;
use App\Http\Requests\Division\Product\AddProductRequest;
use App\Http\Requests\Division\Product\RemoveProductRequest;

class DivisionController extends Controller
{
    public function index()
    {
        $divisions = Division::all();
        return view('divisions.index', compact('divisions'));
    }

    public function create()
    {
        return view('divisions.create');
    }

    public function store(CreateDisionRequest $request)
    {
        $division = new Division();
        $division->name = $request->name;
        $division->user_id = auth()->user()->id;
        $division->save();

        return redirect()->route('divisions', $division)->with('success', 'Подразделение успешно создано');
    }

    public function show(Division $division)
    {
        $products = $division->products()->get();
        return view('divisions.show', compact('division', 'products'));
    }

    public function edit(Division $division)
    {
        return view('divisions.edit', compact('division'));
    }

    public function update(Request $request, Division $division)
    {
        return redirect()->route('divisions');
    }

    public function delete(Division $division)
    {
        return redirect()->route('divisions');
    }

    public function getProductsForModal(Division $division)
    {
        $products = Product::whereNotIn('id', $division->products()->pluck('id'))->get();
        
        return response()->json($products);
    }

    public function addProduct(AddProductRequest $request)
    {
        $division->products()->attach($request->product_id);
        return redirect()->route('divisions.show', $division);
    }

    public function removeProduct(RemoveProductRequest $request)
    {
        $division->products()->detach($request->product_id);
        return redirect()->route('divisions.show', $division);
    }
}
