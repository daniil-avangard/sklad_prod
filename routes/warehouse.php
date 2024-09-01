<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ArivalController;

// Инвентаризация Отображаются продкты и их кол-во на складе
Route::get('/inventories', [InventoryController::class, 'index'])->name('inventories');
Route::get('/inventories/create', [InventoryController::class, 'create'])->name('inventories.create');
Route::post('/inventories', [InventoryController::class, 'store'])->name('inventories.store');
Route::get('/inventories/{inventory}', [InventoryController::class, 'show'])->name('inventories.show');

// Приход
Route::get('/arivals', [ArivalController::class, 'index'])->name('arivals');
Route::get('/arivals/create', [ArivalController::class, 'create'])->name('arivals.create');
Route::post('/arivals', [ArivalController::class, 'store'])->name('arivals.store');
Route::get('/arivals/{arival}', [ArivalController::class, 'show'])->name('arivals.show');
Route::get('/arivals/{arival}/edit', [ArivalController::class, 'edit'])->name('arivals.edit');
Route::put('/arivals/{arival}', [ArivalController::class, 'update'])->name('arivals.update');
Route::delete('/arivals/{arival}', [ArivalController::class, 'delete'])->name('arivals.delete');