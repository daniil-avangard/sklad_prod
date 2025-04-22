<?php

use App\Enum\UserRoleEnum;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\ProductVariantsController;
use App\Http\Controllers\CompanyController;
use App\Models\Company;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DivisionGroupController;
use App\Http\Controllers\DivisionGroupDivisionController;
use App\Http\Controllers\ProductGroupDivisionController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

Route::middleware('auth', 'admin')->group(function () {
    Route::get('/', function () {
        $user = Auth::user();
        $role = $user->roles()->first()?->value;
        // dd($role);

        if ($role === UserRoleEnum::MANAGER->value) {
            return redirect()->route('products.list');
        } else if ($role === UserRoleEnum::DIVISION_MANAGER->value) {
            return redirect()->route('products');
        } else {
            return redirect()->route('orders.new');
        }
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

    // Добавляет продукты Подразделениям
    Route::post('/products/{product}/divisions/', [ProductController::class, 'toggleDivision'])->name('products.divisions.toggleDivision');
    Route::post('/products/{product}/divisions-all', [ProductController::class, 'addAllDivisions'])->name('products.divisions.addAllDivisions');
    Route::delete('/products/{product}/divisions-all', [ProductController::class, 'deleteAllDivisions'])->name('products.divisions.deleteAllDivisions');
    Route::post('/products/{product}/divisions-by-category', [ProductController::class, 'addDivisionByCategory'])->name('products.divisions.addDivisionsByCategory');
    Route::delete('/products/{product}/divisions-by-category', [ProductController::class, 'deleteDivisionByCategory'])->name('products.divisions.deleteDivisionsByCategory');


    // Группы подразделений
    Route::get('/products/{product}/groups/divisions/create', [ProductGroupDivisionController::class, 'create'])->name('products.groups.divisions.create');
    Route::post('/products/{product}/groups/divisions', [ProductGroupDivisionController::class, 'attach'])->name('products.groups.divisions.attach');
    Route::delete('/products/{product}/groups/divisions/{division}', [ProductGroupDivisionController::class, 'detach'])->name('products.groups.divisions.detach');


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


    // Подразделения
    Route::get('/divisions', [DivisionController::class, 'index'])->name('divisions');
    Route::get('/divisions/create', [DivisionController::class, 'create'])->name('divisions.create');
    Route::post('/divisions', [DivisionController::class, 'store'])->name('divisions.store');

    // Новые
    Route::get('/division-category', [DivisionController::class, 'getDivisionList'])->name('division-category');
    Route::post('/division-category', [DivisionController::class, 'addCategory'])->name('division-category.create');
    Route::delete('/division-category', [DivisionController::class, 'deleteCategory'])->name('division-category.delete');


    Route::get('/divisions/{division}', [DivisionController::class, 'show'])->name('divisions.show');
    Route::put('/divisions/{division}', [DivisionController::class, 'update'])->name('divisions.update');
    Route::delete('/divisions/{division}', [DivisionController::class, 'delete'])->name('divisions.delete');

    Route::get('/divisions/{division}/products/modal', [DivisionController::class, 'getProductsForModal'])->name('divisions.products.modal');
    Route::post('/divisions/{division}/products', [DivisionController::class, 'addProduct'])->name('divisions.addProduct');
    Route::delete('/divisions/{division}/products/{product}', [DivisionController::class, 'removeProduct'])->name('divisions.removeProduct');

    Route::get('/groups/divisions', [DivisionGroupController::class, 'index'])->name('groups.divisions');
    Route::get('/groups/divisions/create', [DivisionGroupController::class, 'create'])->name('groups.divisions.create');
    Route::post('/groups/divisions', [DivisionGroupController::class, 'store'])->name('groups.divisions.store');
    Route::get('/groups/divisions/{group}', [DivisionGroupController::class, 'show'])->name('groups.divisions.show');
    Route::get('/groups/divisions/{group}/edit', [DivisionGroupController::class, 'edit'])->name('groups.divisions.edit');
    Route::put('/groups/divisions/{group}', [DivisionGroupController::class, 'update'])->name('groups.divisions.update');
    Route::delete('/groups/divisions/{group}', [DivisionGroupController::class, 'delete'])->name('groups.divisions.delete');

    Route::get('/groups/{group}/divisions/create', [DivisionGroupDivisionController::class, 'create'])->name('groups.divisions.division.create');
    Route::post('/groups/{group}/divisions/attach', [DivisionGroupDivisionController::class, 'attach'])->name('groups.divisions.division.attach');
    Route::post('/groups/{group}/divisions/detach', [DivisionGroupDivisionController::class, 'detach'])->name('groups.divisions.division.detach');



    Route::get('/companies', [CompanyController::class, 'index'])->name('companies');
    Route::get('companies/create', [CompanyController::class, 'create'])->name('companies.create');
    Route::post('/companies', [CompanyController::class, 'store'])->name('companies.store');
    Route::get('companies/{company}', [CompanyController::class, 'show'])->name('companies.show');
    Route::get('companies/{company}/edit', [CompanyController::class, 'edit'])->name('companies.edit');
    Route::put('companies/{company}', [CompanyController::class, 'update'])->name('companies.update');
    Route::delete('companies/{company}', [CompanyController::class, 'delete'])->name('companies.delete');
});
