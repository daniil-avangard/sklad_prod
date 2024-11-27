<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Product\ProductRequest;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Models\Company;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Arival;
use App\Models\ProductVariant;
use App\Models\Division;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\AuthorizationException;
use App\Models\Category;
use App\Models\DivisionCategory;
use App\Models\Writeoff;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProductController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        if (Gate::denies('view', Product::class)) {
            throw new AuthorizationException('У вас нет разрешения на просмотр продуктов.');
        }

        $canCreateProduct = Gate::allows('create', Product::class);

        $products = Product::with('variants')->get()->map(function ($product) {
            $product->total_quantity = $product->variants->sum('quantity');
            $product->total_reserved = $product->variants->sum('reserved');
            return $product;
        });
        return view('products.index', compact('products', 'canCreateProduct'));
    }

    public function create()
    {
        if (Gate::denies('create', Product::class)) {
            throw new AuthorizationException('У вас нет разрешения на создание продуктов.');
        }

        $companies = Company::all();
        $categories = Category::all();
        return view('products.create', compact('companies', 'categories'));
    }

    public function store(ProductRequest $productRequest)
    {
        if (Gate::denies('create', Product::class)) {
            throw new AuthorizationException('У вас нет разрешения на создание продуктов.');
        }

        $data = $productRequest->only([
            'name',
            'description',
            'company_id',
            'kko_operator',
            'express_operator',
            'sku',
            'category_id'
        ]);
        $data['user_id'] = Auth::user()->id;

        // Обработка чекбоксов
        $checkboxFields = ['kko_hall', 'kko_account_opening', 'kko_manager', 'express_hall'];
        foreach ($checkboxFields as $field) {
            $data[$field] = $productRequest->has($field);
        }

        // Создаем новый продукт для получения его ID
        $product = Product::create($data);

        // Создаем директорию для изображений продукта
        $imagePath = 'products/' . $product->id;

        // Сохраняем изображение в новую директорию
        if ($productRequest->hasFile('image')) {
            $data['image'] = $productRequest->file('image')->store($imagePath, 'public');

            // Обновляем продукт с путем к изображению
            $product->update(['image' => $data['image']]);
        }

        $variant = new ProductVariant();
        $variant->product_id = $product->id;
        $variant->quantity = 0;
        $variant->is_active = true;
        $variant->reserved = 0;
        $variant->sku = $product->sku;
        $variant->save();

        return redirect()->route('products.show', $product)->with('success', 'Продукт успешно добавлен');
    }


    // Обновляю
    public function show(Request $request, Product $product)
    {
        if (Gate::denies('view', $product)) {
            throw new AuthorizationException('У вас нет разрешения на просмотр продуктов.');
        }

        $allDivisions = DivisionCategory::with('divisions')->get()->map(function ($category) use ($product) {
            return [
                'category_id' => $category->id, // Id категории
                'category_name' => $category->category_name, // Имя категории
                'divisions' => $category->divisions->map(function ($division) use ($product) {
                    return [
                        'division' => $division,
                        'is_active' => $product->divisions->contains($division)
                    ];
                })
            ];
        });

        // Фильтруем все выбранные активные подразделения
        $selectedDivisions = $allDivisions->flatMap(function ($category) {
            // Для каждой категории фильтруем активные подразделения
            return $category['divisions']->filter(function ($division) {
                return $division['is_active'];
            });
        });

        // Получаем количество выбранных активных подразделений
        $selectedDivisionsCount = $selectedDivisions->count();

        // Получаем общее количество всех подразделений (по категориям)
        $allDivisionsCount = $allDivisions->flatMap(function ($category) {
            return $category['divisions'];
        })->count();

        // Проверка, выбраны ли все подразделения
        $isAllDivisionsSelected = $selectedDivisionsCount === $allDivisionsCount;

        $variants = $product->variants()->orderBy('date_of_actuality', 'desc')->get();

        $arivals = $product->arivalProduct()->with('arival')->get()->map(function ($arivalProduct) {
            return [
                'arival' => $arivalProduct->arival,
                'quantity' => $arivalProduct->quantity
            ];
        })->unique('arival.id');

        $writeOffs = $product->writeOffProduct()->with('writeOff')->get()->map(function ($writeOffProduct) {
            return [
                'writeOff' => $writeOffProduct->writeOff,
                'quantity' => $writeOffProduct->quantity
            ];
        })->unique('writeOff.id');

        return view('products.show', compact('product', 'allDivisions', 'isAllDivisionsSelected', 'arivals', 'writeOffs', 'variants'));
    }

    public function edit(Product $product)
    {
        if (Gate::denies('update', $product)) {
            throw new AuthorizationException('У вас нет разрешения на редактирование продуктов.');
        }

        $companies = Company::all();
        $categories = Category::all();
        return view('products.edit', compact('product', 'companies', 'categories'));
    }

    public function update(UpdateProductRequest $productRequest, Product $product)
    {
        if (Gate::denies('update', $product)) {
            throw new AuthorizationException('У вас нет разрешения на редактирование продуктов.');
        }
        $data = $productRequest->only([
            'name',
            'description',
            'company_id',
            'sku',
            'kko_operator',
            'express_operator',
            'category_id'
        ]);
        $data['user_id'] = Auth::user()->id;
        // Обработка чекбоксов
        $checkboxFields = ['kko_hall', 'kko_account_opening', 'kko_manager', 'express_hall'];
        foreach ($checkboxFields as $field) {
            $data[$field] = $productRequest->has($field);
        }
        $imagePath = 'products/' . $product->id;
        if ($productRequest->hasFile('image')) {

            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $productRequest->file('image')->store($imagePath, 'public');
        } elseif ($productRequest->input('delete_image') == 1) {
            if ($productRequest->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = null;
        }

        // Обновляем продукт с путем к изображению
        $product->update($data);

        return redirect()->route('products.show', $product)->with('success', 'Продукт успешно обновлен');
    }

    public function delete(Product $product)
    {
        if (Gate::denies('delete', $product)) {
            throw new AuthorizationException('У вас нет разрешения на удаление продуктов.');
        }

        // Удаляем связанные записи в division_product
        $product->divisions()->detach();

        $product->arivalProduct()->delete();
        $product->writeOffProduct()->delete();

        $product->variants()->delete();

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('products')->with('success', 'Продукт успешно удален');
    }


    public function arival(Product $product)
    {
        // if (Gate::denies('view', $product)) {
        //     throw new AuthorizationException('У вас нет разрешения на просмотр продуктов.');
        // }

        $this->authorize('view', Arival::class);

        $arivals = $product->arivalProduct()->with('arival')->orderByDesc('created_at')->get()->map(function ($arivalProduct) {
            return [
                'arival' => $arivalProduct->arival,
                'quantity' => $arivalProduct->quantity
            ];
        })->unique('arival.id');
        return view('products.arivals.index', compact('product', 'arivals'));
    }

    public function writeoff(Product $product)
    {
        // if (Gate::denies('view', $product)) {
        //     throw new AuthorizationException('У вас нет разрешения на просмотр продуктов.');
        // }

        $this->authorize('view', Writeoff::class);

        $writeoffs = $product->writeOffProduct()->with('writeOff')->get()->map(function ($writeOffProduct) {
            return [
                'writeoff' => $writeOffProduct->writeOff,
                'quantity' => $writeOffProduct->quantity
            ];
        })->unique('writeOff.id');

        return view('products.writeoffs.index', compact('product', 'writeoffs'));
    }

    public function addAllDivisions(Request $request)
    {
        if (Gate::denies('update', \App\Models\Product::class)) {
            throw new AuthorizationException('У вас нет разрешения на редактирование продуктов.');
        }

        // Приводим значения к числовым типам
        $product_id = (int) $request->product_id;
        $product = Product::findOrFail($product_id);
        $divisionIds = Division::pluck('id');
        $product->divisions()->syncWithoutDetaching($divisionIds);

        return response()->json([
            'success' => true,
        ]);
    }

    public function deleteAllDivisions(Request $request)
    {
        if (Gate::denies('update', \App\Models\Product::class)) {
            throw new AuthorizationException('У вас нет разрешения на редактирование продуктов.');
        }

        // Приводим значения к числовым типам
        $product_id = (int) $request->product_id;
        $product = Product::findOrFail($product_id);
        $product->divisions()->detach();

        return response()->json([
            'success' => true,
        ]);
    }



    public function addDivisionsByCategory(Request $request)
    {
        if (Gate::denies('update', \App\Models\Product::class)) {
            throw new AuthorizationException('У вас нет разрешения на редактирование продуктов.');
        }

        // Приводим значения к числовым типам
        $product_id = (int) $request->product_id;
        $division_category_id = (int) $request->division_category_id;

        // Получаем все подразделения, относящиеся к указанной категории
        $divisionIds = Division::whereHas('divisionCategory', function ($query) use ($division_category_id) {
            $query->where('id', $division_category_id);
        })->pluck('id');
        $product = Product::findOrFail($product_id);
        $product->divisions()->syncWithoutDetaching($divisionIds);

        return response()->json([
            'success' => true,
            'body' => $divisionIds->toArray(),
        ]);
    }

    public function toggleDivision(Request $request)
    {
        if (Gate::denies('update', \App\Models\Product::class)) {
            throw new AuthorizationException('У вас нет разрешения на редактирование продуктов.');
        }

        // Приводим значения к числовым типам
        $product_id = (int) $request->product_id;
        $division_id = $request->division_id;
        $product = Product::findOrFail($product_id);

        // Выполняем toggle и получаем добавленные/удалённые ID
        $changes = $product->divisions()->toggle($division_id);
        $isAllDivisionSelected = $product->divisions()->count() === Division::all()->count();

        return response()->json([
            'success' => true,
            'added' => $changes['attached'],
            'removed' => $changes['detached'],
            'isAllSelected' => $isAllDivisionSelected,
        ]);
    }
}