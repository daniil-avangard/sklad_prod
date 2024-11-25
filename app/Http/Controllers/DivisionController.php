<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Division;
use App\Http\Requests\Division\CreateDisionRequest;
use App\Http\Requests\Division\Product\AddProductRequest;
use App\Http\Requests\Division\Product\RemoveProductRequest;
use App\Models\DivisionCategory;
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

        // Загружаем данные вместе с категориями
        $divisions = Division::with('divisionCategory')->get();

        // Преобразуем категории в строку
        $divisions = $divisions->map(function ($division) {
            $division->division_category = $division->divisionCategory->first()?->category_name; // Берём только имя категории
            unset($division->divisionCategory); // Удаляем исходную связь, если не нужна
            return $division;
        });

        return view('divisions.index', compact('divisions', 'canCreateProduct'));
    }

    public function create()
    {
        // Получает все подразделения
        $divisionCategory = DivisionCategory::select('id', 'category_name')->get();

        // Получает все подразделения
        // $divisions = DivisionCategory::pluck('division_category');

        return view('divisions.create', compact('divisionCategory'));
    }

    public function store(Request $request)
    {
        $category_id = $request->category_id;

        $divisionCategoryIds = DivisionCategory::pluck('id')->toArray();

        // Проверка на допустимые категории
        if (!in_array($category_id, $divisionCategoryIds)) {
            return response()->json([
                'success' => false,
                'message' => 'Невалидная категория',
            ]);
        }


        $division = new Division();
        $division->city = $request->city;
        $division->name = $request->department;
        $division->user_id = auth()->user()->id;
        $division->save();


        // Добавляем запись в таблицу связи
        $division->divisionCategory()->attach($category_id);

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

    public function addCategory(Request $request)
    {
        $divisionCategory = new DivisionCategory();

        $divisionCategory->category_name = $request->division_category;
        $divisionCategory->user_id = auth()->user()->id;
        $divisionCategory->save();

        return response()->json([
            'success' => true,
            'message' => 'Категория ' . $divisionCategory->category_name . ' успешно создана',
        ]);
    }
}