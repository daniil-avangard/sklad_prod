<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ArivalController;


// Приход
Route::get('/arivals', [ArivalController::class, 'index'])->name('arivals');
Route::get('/arivals/create', [ArivalController::class, 'create'])->name('arivals.create');
Route::post('/arivals', [ArivalController::class, 'store'])->name('arivals.store');
Route::get('/arivals/{arival}', [ArivalController::class, 'show'])->name('arivals.show');
Route::get('/arivals/{arival}/edit', [ArivalController::class, 'edit'])->name('arivals.edit');
Route::put('/arivals/{arival}', [ArivalController::class, 'update'])->name('arivals.update');
Route::delete('/arivals/{arival}', [ArivalController::class, 'delete'])->name('arivals.delete');

    // Принятие прихода
    Route::get('/arivals/{arival}/accepted', [ArivalController::class, 'accepted'])->name('arivals.accepted');
    // Отклонение прихода
    Route::get('/arivals/{arival}/rejected', [ArivalController::class, 'rejected'])->name('arivals.rejected');

