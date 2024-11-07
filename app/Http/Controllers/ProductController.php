<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Product\ProductRequest;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Models\Company;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\ProductVariant;
use App\Models\Division;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\AuthorizationException;
use App\Models\Category;

class ProductController extends Controller
{
    public function index()
    {

    if (Gate::denies('view', Product::class)) {
        throw new AuthorizationException('У вас нет разрешения на просмотр продуктов.');
    }

        $products = Product::with('variants')->get()->map(function ($product) {
            $product->total_quantity = $product->variants->sum('quantity');
            $product->total_reserved = $product->variants->sum('reserved');
            return $product;
        });
        return view('products.index', compact('products'));
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

    public function show(Product $product)
    {
        if (Gate::denies('view', $product)) {
            throw new AuthorizationException('У вас нет разрешения на просмотр продуктов.');
        }

        $divisions = $product->divisions()->get();
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

        return view('products.show', compact('product', 'divisions', 'arivals', 'writeOffs', 'variants'));
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
        if (Gate::denies('view', $product)) {
            throw new AuthorizationException('У вас нет разрешения на просмотр продуктов.');
        }

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
        if (Gate::denies('view', $product)) {
            throw new AuthorizationException('У вас нет разрешения на просмотр продуктов.');
        }

        $writeoffs = $product->writeOffProduct()->with('writeOff')->get()->map(function ($writeOffProduct) {
            return [
                'writeoff' => $writeOffProduct->writeOff,
                'quantity' => $writeOffProduct->quantity
            ];
        })->unique('writeOff.id');

        return view('products.writeoffs.index', compact('product', 'writeoffs'));
    }

    public function createDivision(Product $product)
    {
        if (Gate::denies('update', $product)) {
            throw new AuthorizationException('У вас нет разрешения на редактирование продуктов.');
        }

        $divisions = Division::query()
            ->whereNotIn('id', $product->divisions()->pluck('id'))
            ->oldest('name')
            ->get();


        return view('products.divisions.create', compact('divisions', 'product'));
    }



    public function addDivision(Product $product, Request $request)
    {
        if (Gate::denies('update', $product)) {
            throw new AuthorizationException('У вас нет разрешения на редактирование продуктов.');
        }

        $product->divisions()->syncWithoutDetaching($request->division_id);

        return redirect()->route('products.show', $product)->with('success', 'Подразделение успешно добавлено');
    }

    public function addAllDivisions(Product $product)
    {
        if (Gate::denies('update', $product)) {
            throw new AuthorizationException('У вас нет разрешения на редактирование продуктов.');
        }

        $product->divisions()->syncWithoutDetaching(Division::pluck('id'));

        return redirect()->route('products.show', $product)->with('success', 'Все подразделения успешно добавлены');
    }

    public function removeDivision(Product $product, Division $division)
    {
        if (Gate::denies('update', $product)) {
            throw new AuthorizationException('У вас нет разрешения на редактирование продуктов.');
        }

        $product->divisions()->detach($division->id);

        return redirect()->route('products.show', $product)->with('success', 'Подразделение успешно удалено');
    }
}
