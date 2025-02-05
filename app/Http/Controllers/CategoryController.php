<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Requests\Category\CreateCategoryRequest;
use App\Models\Product;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Str;
use \Illuminate\Support\Facades\Gate;

class CategoryController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('view', Product::class);
        $canCreateProduct = Gate::allows('create', Product::class);

        $categories = Category::orderBy('name')->get();
        return view('categories.index', compact('categories', 'canCreateProduct'));
    }

    public function create()
    {
        $this->authorize('create', Product::class);

        return view('categories.create');
    }

    public function store(CreateCategoryRequest $request)
    {
        $data = $request->only(['name', 'description']);
        $data['slug'] = Str::slug($request->name);
        $data['user_id'] = auth()->user()->id;

        Category::create($data);
        return redirect()->route('categories')->with('success', 'Категория успешно создана');
    }

    public function edit(Category $category)
    {
        $this->authorize('update', Product::class);

        return view('categories.edit', compact('category'));
    }

    public function update(CreateCategoryRequest $request, Category $category)
    {
        $data = $request->only(['name', 'description']);
        $data['slug'] = Str::slug($request->name);
        $data['user_id'] = auth()->user()->id;
        $category->update($data);
        return redirect()->route('categories')->with('success', 'Категория успешно обновлена');
    }
}