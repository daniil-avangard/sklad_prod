<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Product\ProductRequest;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Models\Company;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Product\UpdateProductRequest;
class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $companies = Company::all();
        return view('products.create', compact('companies'));
    }

    public function store(ProductRequest $productRequest)
    {
        $data = $productRequest->only(['name', 'description', 'company_id', 'kko_hall', 'kko_account_opening', 'kko_manager', 'kko_operator', 'express_hall', 'express_operator', 'sku']);
        $data['user_id'] = Auth::user()->id;
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

        if ($productRequest->input('action') === 'save_and_add_variant') {
            return redirect()->route('product.variants.create', $product)->with('success', 'Продукт успешно добавлен. Теперь добавьте вариант.');
        }

        return redirect()->route('products')->with('success', 'Продукт успешно добавлен');
    }

    public function show(Product $product)
    {
        $divisions = $product->divisions()->get();

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
        
        return view('products.show', compact('product', 'divisions', 'arivals', 'writeOffs'));
    }

    public function edit(Product $product)
    {
        $companies = Company::all();
        return view('products.edit', compact('product', 'companies'));
    }

    public function update(UpdateProductRequest $productRequest, Product $product)
    {
        $data = $productRequest->only(['name', 'description', 'company_id', 'sku']);
        // Обработка чекбоксов
        $checkboxFields = ['kko_hall', 'kko_account_opening', 'kko_manager', 'express_hall'];
        foreach ($checkboxFields as $field) {
            $data[$field] = $productRequest->has($field);
        }
        $imagePath = 'products/' . $product->id;
        if ($productRequest->hasFile('image')) {

            if($product->image){
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $productRequest->file('image')->store($imagePath, 'public');
        } elseif($productRequest->input('delete_image') == 1){
            if($productRequest->image){
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = null;
        }
            
            // Обновляем продукт с путем к изображению
        $product->update($data);

        return redirect()->route('products')->with('success', 'Продукт успешно обновлен');
    }

    public function delete(Product $product)
    {
        $product->delete();

        return redirect()->route('products')->with('success', 'Продукт успешно удален');
    }
}
