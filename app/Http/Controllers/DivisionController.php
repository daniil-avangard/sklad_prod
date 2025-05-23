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
        // Получает все категории подразделений
        $divisionCategory = DivisionCategory::select('id', 'category_name')->get();

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
                'message' => 'Выбрана несуществующая категория',
            ]);
        }

        $division = new Division();
        $division->city = $request->city;
        $division->name = $request->department;
        $division->user_id = auth()->user()->id;
        $division->sort_for_excel = $request->sort_for_excel;
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
        // Получает все категории подразделений
        $divisionCategory = DivisionCategory::select('id', 'category_name')->get();

        $products = $division->products()->get();
        $currentCategory = $division->divisionCategory->first();
        $divisionCategoryId = $currentCategory?->pivot?->division_category_id;

        return view('divisions.show', compact('division', 'products', 'divisionCategory', 'divisionCategoryId'));
    }

    public function edit(Division $division)
    {

        $this->authorize('update', Product::class);

        // Получает все категории подразделений
        $divisionCategory = DivisionCategory::select('id', 'category_name')->get();

        $products = $division->products()->get();
        $currentCategory = $division->divisionCategory->first();
        $divisionCategoryId = $currentCategory?->pivot?->division_category_id;

        return view('divisions.edit', compact('division', 'products', 'divisionCategory', 'divisionCategoryId'));
    }

    public function update(Request $request, Division $division)
    {
        $this->authorize('update', Division::class);

        // dd($request->toArray());

        // Получаем данные из запроса
        $category_id = $request->input('category_id');
        $city = $request->input('city');
        $department = $request->input('department');
        $sort_for_excel = $request->input('sort_for_excel');

        // Проверка на допустимые категории
        if ($category_id && !DivisionCategory::where('id', $category_id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Выбрана несуществующая категория',
            ], 400);
        }

        // Обновление данных подразделения
        $division->update([
            'city' => $city,
            'name' => $department, // Предполагается, что поле "Отдел" хранится в поле `name`
            'sort_for_excel' => $sort_for_excel ?? null,
        ]);

        // Обновление связи с категорией
        if ($category_id) {
            $division->divisionCategory()->sync($category_id); // Обновляем связь
        } else {
            $division->divisionCategory()->detach(); // Удаляем связь, если категория не выбрана
        }

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

    public function deleteCategory(Request $request)
    {
        $divisionCategoryIds = $request->division_ids;

        // Проверяем, что переданы ID
        if (empty($divisionCategoryIds)) {
            return response()->json([
                'success' => false,
                'message' => 'Не указаны ID категорий для удаления.',
            ], 400);
        }

        // Инициализируем переменные для отчета
        $deletedCount = 0;
        $errors = [];

        // Проходим по каждой категории
        foreach ($divisionCategoryIds as $categoryId) {
            $category = DivisionCategory::find($categoryId);

            if (!$category) {
                $errors[] = "Категория с ID $categoryId не найдена.";
                continue;
            }

            // Проверяем, используется ли категория
            if ($category->divisions()->exists()) {
                $errors[] = "Категория {$category->category_name} используется. Её нельзя удалить.";
                continue;
            }

            // Удаляем категорию
            $category->delete();
            $deletedCount++;
        }

        // Формируем сообщение об успехе или ошибке
        if ($deletedCount > 0 && empty($errors)) {
            $divisionCategory = DivisionCategory::select('id', 'category_name')->get();

            return response()->json([
                'success' => true,
                'body' => $divisionCategory,
                'message' => count($divisionCategoryIds) > 1
                    ? 'Категории успешно удалены.'
                    : 'Категория успешно удалена.',
            ]);
        }

        // Если были ошибки, возвращаем их
        return response()->json([
            'success' => false,
            'message' => 'Ошибка при удалении категорий.',
        ], 400);
    }

    public function getDivisionList(Request $request)
    {
        $divisionCategory = DivisionCategory::select('id', 'category_name')->get();

        return response()->json([
            'success' => true,
            'body' => $divisionCategory,
        ]);
    }
}
