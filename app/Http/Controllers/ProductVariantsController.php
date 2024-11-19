<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Requests\Product\Variant\CreateVariantRequest;
use App\Models\ProductVariant;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProductVariantsController extends Controller
{
    use AuthorizesRequests;

    public function index(Product $product)
    {

        return view('products.variants.index', compact('product'));
    }

    public function create(Product $product)
    {
        $this->authorize('create', \App\Models\ProductVariant::class);

        return view('products.variants.create', compact('product'));
    }

    public function store(Product $product, CreateVariantRequest $request)
    {

        $data = $request->only(['is_active', 'reserved', 'date_of_actuality']);

        // Обработка чекбокса is_active
        $data['is_active'] = $request->has('is_active');

        if ($request->date_of_actuality) {
            $sku = $product->sku . '-' . date('dmY', strtotime($request->date_of_actuality));
        } else {
            $sku = $product->sku;
        }

        // Проверяем, существует ли вариант продукта с таким же SKU
        if (ProductVariant::where('sku', $sku)->exists()) {
            return redirect()->back()->withErrors(['sku' => 'Вариант продукта с такой датой актуальности уже существует'])->withInput();
        }


        $data['sku'] = $sku;
        $data['product_id'] = $product->id;

        if ($request->hasFile('pdf_maket')) {
            // Получаем загруженный файл изображения из запроса
            $image = $request->file('pdf_maket');

            // Создаем уникальное имя файла, используя текущее время и оригинальное расширение файла
            $imageName = time() . '.' . $image->getClientOriginalExtension();

            // Формируем путь для сохранения изображения варианта продукта в директории Storage
            $path = 'products/' . $product->id . '/variants';

            // Сохраняем файл в директорию Storage и получаем путь к файлу
            $filePath = $image->storeAs($path, $imageName, 'public');
            // Сохраняем относительный путь к файлу в массив данных для создания варианта продукта
            $data['image'] = $filePath;
        }

        $variant = ProductVariant::create($data);

        return redirect()->route('products', $product)->with('success', 'Вариант успешно создан');
    }

    public function edit(Product $product, ProductVariant $variant)
    {
        return view('products.variants.edit', compact('product', 'variant'));
    }

    public function update(Product $product, ProductVariant $variant, Request $request)
    {
        $data = $request->only(['is_active', 'reserved', 'date_of_actuality']);
        $data['reserved'] ? $data['reserved'] = $request->reserved : $data['reserved'] = 0;
        // Обработка чекбокса is_active
        $data['is_active'] = $request->has('is_active');

        if ($request->date_of_actuality) {
            $sku = $product->sku . '-' . date('dmY', strtotime($request->date_of_actuality));
        } else {
            $sku = $product->sku;
        }

        // Проверяем, существует ли вариант продукта с таким же SKU
        if (ProductVariant::where('sku', $sku)->where('id', '!=', $variant->id)->exists()) {
            return redirect()->back()->withErrors(['sku' => 'Вариант продукта с такой датой актуальности уже существует'])->withInput();
        }

        if ($request->reserved > $variant->quantity) {
            return redirect()->back()->withErrors(['reserved' => 'Количество резерва не может быть больше количества товара'])->withInput();
        }

        $data['sku'] = $sku;
        $data['product_id'] = $product->id;

        if ($request->hasFile('pdf_maket')) {
            // Получаем загруженный файл изображения из запроса
            $image = $request->file('pdf_maket');

            // Создаем уникальное имя файла, используя текущее время и оригинальное расширение файла
            $imageName = time() . '.' . $image->getClientOriginalExtension();

            // Формируем путь для сохранения изображения варианта продукта в директории Storage
            $path = 'products/' . $product->id . '/variants';

            // Сохраняем файл в директорию Storage и получаем путь к файлу
            $filePath = $image->storeAs($path, $imageName, 'public');
            // Сохраняем относительный путь к файлу в массив данных для создания варианта продукта
            $data['image'] = $filePath;
        }

        $variant->update($data);

        return redirect()->route('products.show', $product)->with('success', 'Вариант успешно обновлен');
    }

    public function delete(Product $product, ProductVariant $variant)
    {
        $variant->delete();
        return redirect()->route('products.show', $product)->with('success', 'Вариант успешно удален');

   }
}