<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Division;
use App\Http\Requests\Division\CreateDisionRequest;
use App\Http\Requests\Division\Product\AddProductRequest;
use App\Http\Requests\Division\Product\RemoveProductRequest;
use App\Models\Product;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Gate;

class DivisionController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('view', Product::class);
        $canCreateProduct = Gate::allows('create', Product::class);

        $divisions = Division::all();
        return view('divisions.index', compact('divisions', 'canCreateProduct'));
    }

    public function create()
    {
        return view('divisions.create');
    }

    public function store(Request $request)
    {
        $division = new Division();

        $category_id = $request->category_id;

        // Проверка на допустимые категории
        if (!in_array($category_id, [1, 2, 3])) {
            return response()->json([
                'success' => false,
                'message' => 'Невалидная категория',
            ]);
        }

        $division->city = $request->city;
        $division->name = $request->department;
        $division->user_id = auth()->user()->id;
        $division->save();

        return response()->json([
            'success' => true,
            'message' => 'Подразделение успешно создано',
        ]);
    }

    public function show(Division $division)
    {
        $products = $division->products()->get();
        return view('divisions.show', compact('division', 'products'));
    }

    public function edit(Division $division)
    {
        $this->authorize('update', Product::class);

        return view('divisions.edit', compact('division'));
    }

    public function update(Request $request, Division $division)
    {
        $division->update($request->only(['name']));
        return redirect()->route('divisions')->with('success', 'Подразделение успешно обновлено');
    }

    public function delete(Division $division)
    {
        $this->authorize('delete', Product::class);

        $division->products()->detach();
        $division->delete();
        return redirect()->route('divisions')->with('success', 'Подразделение успешно удалено');
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