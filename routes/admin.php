<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\AdminPermissionController;
use App\Http\Controllers\RoleController;



Route::get('/admin/', function () {
    return 'Привет, мир!';
});

Route::middleware(['auth', 'admin'])->group(function () {

    // Полномочия
Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions');
Route::get('/permissions/create', [PermissionController::class, 'create'])->name('permissions.create');
Route::post('/permissions', [PermissionController::class, 'store'])->name('permissions.store');
Route::get('/permissions/{permission}/edit', [PermissionController::class, 'edit'])->name('permissions.edit');
Route::put('/permissions/{permission}', [PermissionController::class, 'update'])->name('permissions.update');
Route::get('/permissions/{permission}', [PermissionController::class, 'show'])->name('permissions.show');
Route::delete('/permissions/{permission}', [PermissionController::class, 'delete'])->name('permissions.delete');


Route::get('/users/{user}/permissions/modal', [AdminPermissionController::class, 'getPermissionsForModal'])
->name('users.permissions.modal');


// полномочия админа
    Route::get('/users/{user}/permissions/create', [AdminPermissionController::class, 'create'])->name('user.permissions.create');
    Route::post('/users/{user}/permissions/attach', [AdminPermissionController::class, 'attach'])->name('user.permissions.attach');
    Route::post('/users/{user}/permissions/detach', [AdminPermissionController::class, 'detach'])->name('user.permissions.detach');


// Роли
Route::get('/roles', [RoleController::class, 'index'])->name('roles');
Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
Route::get('/roles/{role}', [RoleController::class, 'show'])->name('roles.show');
Route::delete('/roles/{role}', [RoleController::class, 'delete'])->name('roles.delete');


});


