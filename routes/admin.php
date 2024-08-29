<?php

use Illuminate\Support\Facades\Route;

Route::get('/admin/', function () {
    return 'Привет, мир!';
});

Route::middleware(['auth', 'admin'])->group(function () {

// полномочия админа
    Route::get('/users/{user}/permissions/create', [AdminPermissionController::class, 'create'])->name('user.permissions.create');
    Route::post('/users/{user}/permissions/attach', [AdminPermissionController::class, 'attach'])->name('user.permissions.attach');
    Route::post('/users/{user}/permissions/detach', [AdminPermissionController::class, 'detach'])->name('user.permissions.detach');
});