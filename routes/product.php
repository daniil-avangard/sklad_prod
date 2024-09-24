<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\ProductVariantsController;
use App\Http\Controllers\CompanyController;
use App\Models\Company;
use App\Http\Controllers\CategoryController;

Route::middleware('auth', 'admin')->group(function () {

    Route::get('/', function () {
        return view('home.index');
    })->name('home');



    Route::get('/products', [ProductController::class, 'index'])->name('products');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'delete'])->name('products.delete');

    Route::get('/products/{product}/arivals', [ProductController::class, 'arival'])->name('products.arival');
    Route::get('/products/{product}/writeoffs', [ProductController::class, 'writeoff'])->name('products.writeoff');

    Route::get('/products/{product}/divisions/create', [ProductController::class, 'createDivision'])->name('products.divisions.create');
    Route::post('/products/{product}/divisions', [ProductController::class, 'addDivision'])->name('products.divisions.addDivision');
    Route::delete('/products/{product}/divisions/{division}', [ProductController::class, 'removeDivision'])->name('products.divisions.removeDivision');
    Route::get('/products/{product}/divisionsall', [ProductController::class, 'addAllDivisions'])->name('products.divisions.addAllDivisions');


    Route::get('/products/{product}/variants', [ProductVariantsController::class, 'index'])->name('products.variants');
    Route::get('/products/{product}/variants/create', [ProductVariantsController::class, 'create'])->name('products.variants.create');
    Route::post('/products/{product}/variants', [ProductVariantsController::class, 'store'])->name('products.variants.store');
    Route::get('/products/{product}/variants/{variant}', [ProductVariantsController::class, 'show'])->name('products.variants.show');
    Route::get('/products/{product}/variants/{variant}/edit', [ProductVariantsController::class, 'edit'])->name('products.variants.edit');
    Route::put('/products/{product}/variants/{variant}', [ProductVariantsController::class, 'update'])->name('products.variants.update');
    Route::delete('/products/{product}/variants/{variant}', [ProductVariantsController::class, 'delete'])->name('products.variants.delete');


    // Категории

    Route::get('/categories', [CategoryController::class, 'index'])->name('categories');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'delete'])->name('categories.delete');


    Route::get('/divisions', [DivisionController::class, 'index'])->name('divisions');
    Route::get('/divisions/create', [DivisionController::class, 'create'])->name('divisions.create');
    Route::post('/divisions', [DivisionController::class, 'store'])->name('divisions.store');
    Route::get('/divisions/{division}', [DivisionController::class, 'show'])->name('divisions.show');
    Route::get('/divisions/{division}/edit', [DivisionController::class, 'edit'])->name('divisions.edit');
    Route::put('/divisions/{division}', [DivisionController::class, 'update'])->name('divisions.update');
    Route::delete('/divisions/{division}', [DivisionController::class, 'delete'])->name('divisions.delete');

    Route::get('/divisions/{division}/products/modal', [DivisionController::class, 'getProductsForModal'])->name('divisions.products.modal');
    Route::post('/divisions/{division}/products', [DivisionController::class, 'addProduct'])->name('divisions.addProduct');
    Route::delete('/divisions/{division}/products/{product}', [DivisionController::class, 'removeProduct'])->name('divisions.removeProduct');


    Route::get('/companies', [CompanyController::class, 'index'])->name('companies');
    Route::get('companies/create', [CompanyController::class, 'create'])->name('companies.create');
    Route::post('/companies', [CompanyController::class, 'store'])->name('companies.store');
    Route::get('companies/{company}', [CompanyController::class, 'show'])->name('companies.show');
    Route::get('companies/{company}/edit', [CompanyController::class, 'edit'])->name('companies.edit');
    Route::put('companies/{company}', [CompanyController::class, 'update'])->name('companies.update');
    Route::delete('companies/{company}', [CompanyController::class, 'delete'])->name('companies.delete');
});
