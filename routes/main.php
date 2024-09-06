<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\ProductVariantsController;


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


    Route::get('/products/{product}/variants', [ProductVariantsController::class, 'index'])->name('products.variants');
    Route::get('/products/{product}/variants/create', [ProductVariantsController::class, 'create'])->name('products.variants.create');
    Route::post('/products/{product}/variants', [ProductVariantsController::class, 'store'])->name('products.variants.store');
    Route::get('/products/{product}/variants/{variant}', [ProductVariantsController::class, 'show'])->name('products.variants.show');
    Route::get('/products/{product}/variants/{variant}/edit', [ProductVariantsController::class, 'edit'])->name('products.variants.edit');
    Route::put('/products/{product}/variants/{variant}', [ProductVariantsController::class, 'update'])->name('products.variants.update');
    Route::delete('/products/{product}/variants/{variant}', [ProductVariantsController::class, 'delete'])->name('products.variants.delete');
   

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


});




