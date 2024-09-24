<?php

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductListController;
use App\Http\Controllers\BasketController;
use App\Http\Controllers\Order\OrderController;



Route::get('/product/list', [ProductListController::class, 'index'])->name('products.list');
Route::get('/product/list/{product}', [ProductListController::class, 'show'])->name('products.info');

Route::get('/basket', [BasketController::class, 'index'])->name('basket');
Route::post('/basket/add/{product}', [BasketController::class, 'add'])
    ->where('product', '[0-9]+')
    ->name('basket.add')
    ->missing(function () {
        throw new NotFoundHttpException();
    });

Route::post('/basket/update/{product}', [BasketController::class, 'updateQuantity'])
    ->where('product', '[0-9]+')
    ->name('basket.update');

Route::get('/basket/remove/{product}', [BasketController::class, 'remove'])
    ->where('product', '[0-9]+')
    ->name('basket.remove');

Route::post('basket/clear', [BasketController::class, 'clear'])->name('basket.clear');

// Order

Route::post('/basket/saveorder', [BasketController::class, 'saveOrder'])->name('basket.saveorder');

Route::get('/orders', [OrderController::class, 'index'])->name('orders');
Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

// Просмотр выбранных заказов
Route::post('/orders/selected', [OrderController::class, 'selected'])->name('orders.selected');
