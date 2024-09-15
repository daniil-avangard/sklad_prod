<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Requests\Category\CreateCategoryRequest;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('name')->get();
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
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
