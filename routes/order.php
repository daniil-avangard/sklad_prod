<?php

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductListController;
use App\Http\Controllers\BasketController;
use App\Http\Controllers\Order\OrderController;


Route::middleware('auth', 'admin')->group(function () {

    Route::get('/product/list', [ProductListController::class, 'index'])->name('products.list');
    Route::get('/product/list/{product}', [ProductListController::class, 'show'])->name('products.info');


    Route::get('/basket', [BasketController::class, 'index'])->name('basket');
    Route::post('/basket/add/{product}', [BasketController::class, 'add'])
        ->where('product', '[0-9]+')
        ->name('basket.add')
        ->missing(function () {
            throw new NotFoundHttpException();
        });
    Route::post('/basket/add-all', [BasketController::class, 'addAll'])->name('basket.add-all');
    Route::post('/basket/update/{product}', [BasketController::class, 'updateQuantity'])
        ->where('product', '[0-9]+')
        ->name('basket.update');

    Route::get('/basket/remove/{product}', [BasketController::class, 'remove'])
        ->where('product', '[0-9]+')
        ->name('basket.remove');

    Route::post('basket/clear', [BasketController::class, 'clear'])->name('basket.clear');

    // Order

    Route::post('/basket/saveorder', [BasketController::class, 'saveOrder'])->name('basket.saveorder');

    Route::post('/orders/update-quantity', [OrderController::class, 'updateQuantity'])->name('orders.update-quantity');
    Route::post('/orders/excelldata', [OrderController::class, 'excellData'])->name('orders.excell-data');
    Route::post('/orders/update-full-order', [OrderController::class, 'updateFullOrder'])->name('orders.update-full-order');
    Route::post('/orders/update-comment-manager', [OrderController::class, 'updateCommentManager'])->name('orders.update-comment-manager');


    Route::get('/orders', [OrderController::class, 'index'])->name('orders');
    Route::get('/ordersNew', [OrderController::class, 'indexNew'])->name('orders.new');

    Route::get('/ordersNewUpdate', [OrderController::class, 'indexNewUpdate'])->name('orders.newupdate');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/edit', [OrderController::class, 'edit'])->name('orders.edit');
    Route::put('/orders/{order}', [OrderController::class, 'update'])->name('orders.update');



    // Кастыль. Роут на каждый статус заказа, для разгроничения политики доступа
    Route::get('/orders/{order}/status/processing', [OrderController::class, 'statusProcessing'])->name('orders.status.processing');
    Route::get('/orders/{order}/status/manager-processing', [OrderController::class, 'statusManagerProcessing'])->name('orders.status.manager-processing');
    Route::get('/orders/{order}/status/transferred-to-warehouse', [OrderController::class, 'statusTransferredToWarehouse'])->name('orders.status.transferred-to-warehouse');
    Route::get('/orders/{order}/status/shipped', [OrderController::class, 'statusShipped'])->name('orders.status.shipped');
    Route::get('/orders/{order}/status/delivered', [OrderController::class, 'statusDelivered'])->name('orders.status.delivered');
    Route::get('/orders/{order}/status/canceled', [OrderController::class, 'statusCanceled'])->name('orders.status.canceled');


    // Просмотр выбранных заказов
    Route::post('/orders/selected', [OrderController::class, 'selected'])->name('orders.selected');

    // Груз доставлен
    Route::post('/orders/shipped', [OrderController::class, 'shipped'])->name('orders.shipped');

});
