<?php

namespace App\Http\Controllers;

use App\Enum\UserRoleEnum;
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
use App\Models\DivisionGroup;
use App\Models\Writeoff;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        if (Gate::denies('view', Product::class)) {
            throw new AuthorizationException('У вас нет разрешения на просмотр продуктов.');
        }

        $canCreateProduct = Gate::allows('create', Product::class);
        $userRole = Auth::user()->roles()->first()?->value;
//        dd($userRole);

        if ($userRole === UserRoleEnum::SUPER_ADMIN->value) {
            $products = Product::with('variants')->orderBy('name')->get()
                ->map(function ($product) {
                    $product->total_quantity = $product->variants->sum('quantity');
                    $product->total_reserved = $product->variants->sum('reserved');
                    $product->companyName = $product->company()->first()->name;
                    $product->categoryName = $product->category()->first()->name;
                    return $product;
                });

            $products = Product::with('variants')->get()->map(function ($product) {
                $product->total_quantity = $product->variants->sum('quantity');
                $product->total_reserved = $product->variants->sum('reserved');
                $product->companyName = $product->company()->first()->name;
                $product->categoryName = $product->category()->first()->name;
                return $product;
            });
        } else if ($userRole === UserRoleEnum::DIVISION_MANAGER->value || $userRole === UserRoleEnum::TOP_MANAGER->value) {
            $divisionId = Auth::user()->division_id;
            $divisionGroupIds = Auth::user()->divisionGroups?->pluck("pivot.division_group_id")->toArray();

            // Шаг 2: Находим все подразделения, связанные с этими группами
            $divisionIds = DB::table('division_division_group')
                ->whereIn('division_group_id', $divisionGroupIds)
                ->pluck('division_id')
                ->unique() // Убираем дубликаты
                ->toArray();

            // Шаг 3: Находим продукты, прикрепленные к этим подразделениям
            $productIds = DB::table('division_product')
                ->whereIn('division_id', $divisionIds)
                ->pluck('product_id')
                ->unique() // Убираем дубликаты
                ->toArray();

            $products = Product::with('variants')
                ->whereIn('id', $productIds)
                ->orWhereHas('divisions', function ($query) use ($divisionId) {
                    $query->where('division_id', $divisionId);
                })
                ->orderBy('name')
                ->get()
                ->map(function ($product) {
                    $product->total_quantity = $product->variants->sum('quantity');
                    $product->total_reserved = $product->variants->sum('reserved');
                    $product->companyName = $product->company()->first()->name;
                    $product->categoryName = $product->category()->first()->name;
                    return $product;
                });
        } else {
            $divisionId = Auth::user()->division_id;
            $divisionGroupProducts = DB::table('division_group_product')
                ->join('division_division_group', 'division_group_product.division_group_id', '=', 'division_division_group.division_group_id')
                ->where('division_division_group.division_id', $divisionId)
                ->pluck('division_group_product.product_id');

            $products = Product::with('variants')
                ->whereIn('id', $divisionGroupProducts)
                ->orWhereHas('divisions', function ($query) use ($divisionId) {
                    $query->where('division_id', $divisionId);
                })
                ->orderBy('name')->get()->map(function ($product) {
                    $product->total_quantity = $product->variants->sum('quantity');
                    $product->total_reserved = $product->variants->sum('reserved');
                    $product->companyName = $product->company()->first()->name;
                    $product->categoryName = $product->category()->first()->name;
                    return $product;
                });
        }

        $productCategories = Category::all();
        $productCompanies = Company::all();
        
    //     dd($products->toArray());
    //     "kko_hall" => true
    // "kko_account_opening" => false
    // "kko_manager" => true
    // "kko_operator" => "no"
    // "express_hall" => false
    // "express_operator" => "no"

        return view('products.index', compact('products', 'canCreateProduct', 'productCategories', 'productCompanies', 'userRole'));
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
            'category_id',
            'min_stock'
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
//        if (Gate::denies('view', $product)) {
//            throw new AuthorizationException('У вас нет разрешения на просмотр продуктов.');
//        }

        $allDivisions = DivisionCategory::with('divisions')->get()
            ->filter(function ($divisionCategory) {
                return $divisionCategory->divisions->isNotEmpty();
            })
            ->map(function ($divisionCategory) use ($product) {
                $divisions = $divisionCategory->divisions->map(function ($division) use ($product) {
                    return [
                        'division' => $division,
                        'is_active' => $product->divisions->contains($division)
                    ];
                });

                $isAllDivisionByCategorySelected =  $divisions->every(function ($division) {
                    return $division['is_active'];
                });

                return [
                    'category_id' => $divisionCategory->id, // Id категории
                    'category_name' => $divisionCategory->category_name, // Имя категории
                    'divisions' => $divisions,
                    'category_division_selected' => $isAllDivisionByCategorySelected,
                ];
            });
        // dd($allDivisions->toArray());

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

    // public function variants(Product $product)
    // {
    //     if (Gate::denies('view', $product)) {
    //         throw new AuthorizationException('У вас нет разрешения на просмотр продуктов.');
    //     }

    //     // $divisions = $product->divisions()->get();
    //     $variants = $product->variants()->orderBy('date_of_actuality', 'desc')->get();
    //     $arivals = $product->arivalProduct()->with('arival')->get()->map(function ($arivalProduct) {
    //         return [
    //             'arival' => $arivalProduct->arival,
    //             'quantity' => $arivalProduct->quantity
    //         ];
    //     })->unique('arival.id');

    //     $writeOffs = $product->writeOffProduct()->with('writeOff')->get()->map(function ($writeOffProduct) {
    //         return [
    //             'writeOff' => $writeOffProduct->writeOff,
    //             'quantity' => $writeOffProduct->quantity
    //         ];
    //     })->unique('writeOff.id');

    //     return view('products.show', compact('product', 'arivals', 'writeOffs', 'variants'));
    // }

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
            'kko_operator',
            'express_operator',
            'category_id',
            'min_stock'
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
//        dd($product->arivalProduct()->with('arival')->get());
        return view('products.arivals.index', compact('product', 'arivals'));
    }

    public function writeoff(Product $product)
    {
        // if (Gate::denies('view', $product)) {
        //     throw new AuthorizationException('У вас нет разрешения на просмотр продуктов.');
        // }

        $this->authorize('view', Writeoff::class);

//        $writeoffs2 = $product->writeoffs->map(function ($writeoff) {
//            return [
//                'writeoff' => $writeoff,
//                'quantity' => $writeoff->pivot->quantity
//            ];
//        });
//        dd($product->writeoffs);
        $writeoffs = $product->writeOffProduct()->with('writeOff')->orderByDesc('created_at')->get()->map(function ($writeOffProduct) {
            return [
                'writeoff' => $writeOffProduct->writeOff,
                'quantity' => $writeOffProduct->quantity
            ];
        })->unique('writeoff.id');
//        dd($product->writeOffProduct()->with('writeOff')->orderByDesc('created_at')->get()->map(function ($writeOffProduct) {
//            return [
//                'writeoff' => $writeOffProduct->writeOff,
//                'quantity' => $writeOffProduct->quantity
//            ];
//        }));
//        
//        dd($product->writeOffProduct()->with('writeOff')->orderByDesc('created_at')->get());
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

    public function addDivisionByCategory(Request $request)
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
        $isAllDivisionSelected = $product->divisions()->count() === Division::all()->count();

        return response()->json([
            'success' => true,
            'body' => $divisionIds->toArray(),
            'isAllSelected' => $isAllDivisionSelected,
        ]);
    }

    public function deleteDivisionByCategory(Request $request)
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
        $product->divisions()->detach($divisionIds);

        $isAllDivisionSelected = $product->divisions()->count() === Division::all()->count();

        return response()->json([
            'success' => true,
            'body' => $divisionIds->toArray(),
            'isAllSelected' => $isAllDivisionSelected,
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

        $divisionCategories = DivisionCategory::with('divisions')->get()->map(function ($divisionCategory) use ($product) {
            $divisions = $divisionCategory->divisions->map(function ($division) use ($product) {
                return [
                    'is_active' => $product->divisions->contains($division)
                ];
            });

            $isAllDivisionByCategorySelected = $divisions->every(function ($division) {
                return $division['is_active'];
            });

            return [
                'division_id' => $divisionCategory->id,
                'is_selected' => $isAllDivisionByCategorySelected
            ];
        });

        // Формируем массив ID категорий, в которых все подразделения выбраны
        $checkedCategoryIds = $divisionCategories->filter(function ($category) {
            return $category['is_selected'];
        })->pluck('division_id')->toArray();

        return response()->json([
            'success' => true,
            'added' => $changes['attached'],
            'removed' => $changes['detached'],
            'isAllSelected' => $isAllDivisionSelected,
            'checkedСategoryIds' => $checkedCategoryIds,
        ]);
    }
}
