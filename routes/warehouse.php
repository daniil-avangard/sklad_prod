<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ArivalController;
use App\Http\Controllers\WriteoffController;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
//use Illuminate\Foundation\Configuration\Middleware;

Route::middleware([ValidateCsrfToken::class, 'auth', 'admin'])->group(function () {


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


    // Списание
    Route::get('/writeoffs', [WriteoffController::class, 'index'])->name('writeoffs');
    Route::get('/writeoffs/create', [WriteoffController::class, 'create'])->name('writeoffs.create');
    Route::post('/writeoffs', [WriteoffController::class, 'store'])->name('writeoffs.store');
    Route::get('/writeoffs/{writeoff}', [WriteoffController::class, 'show'])->name('writeoffs.show');
    Route::get('/writeoffs/{writeoff}/edit', [WriteoffController::class, 'edit'])->name('writeoffs.edit');
    Route::put('/writeoffs/{writeoff}', [WriteoffController::class, 'update'])->name('writeoffs.update');
    Route::delete('/writeoffs/{writeoff}', [WriteoffController::class, 'delete'])->name('writeoffs.delete');

    Route::get('/writeoffs/variants/dates', [WriteoffController::class, 'getVariantsDates'])->name('writeoffs.variants.dates');

    // Принятие списания
    Route::get('/writeoffs/{writeoff}/accepted', [WriteoffController::class, 'accepted'])->name('writeoffs.accepted');
    // Отклонение списания
    Route::get('/writeoffs/{writeoff}/rejected', [WriteoffController::class, 'rejected'])->name('writeoffs.rejected');

    // Сборка
    Route::get('/assembly', [ArivalController::class, 'assembly'])->name('assembly');
    Route::get('/assembly/{order}', [ArivalController::class, 'showAssembl'])->name('assembly.show');
//    Route::post('/assembly/createKorobka', [ArivalController::class, 'createKorobka'])->name('assembly.createKorobka')->middleware(ValidateCsrfToken::class);
    Route::post('/assembly/createKorobka', [ArivalController::class, 'createKorobka'])->name('assembly.createKorobka');
    Route::post('/assembly/updateKorobka', [ArivalController::class, 'updateKorobka'])->name('assembly.updateKorobka');
    Route::post('/assembly/korobkaChangeStatus', [ArivalController::class, 'korobkaChangeStatus'])->name('assembly.korobkaChangeStatus');

});
