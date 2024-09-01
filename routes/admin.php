<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserPermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\Roles\RolePermissionController;
use App\Http\Controllers\UserRoleController;





Route::get('/admin/', function () {
    return 'Привет, мир!';
});

Route::middleware(['auth', 'admin'])->group(function () {


    // роли админа
    Route::get('users/{user}/roles/create', [UserRoleController::class, 'create'])->name('user.roles.create');
    Route::post('users/{user}/roles/attach', [UserRoleController::class, 'attach'])->name('user.roles.attach');
    Route::post('users/{user}/roles/detach', [UserRoleController::class, 'detach'])->name('user.roles.detach');

    Route::get('users/{user}/roles/modal', [UserRoleController::class, 'getRolesForModal'])
    ->name('user.roles.modal');


// полномочия админа
    Route::get('/users/{user}/permissions/create', [UserPermissionController::class, 'create'])->name('user.permissions.create');
    Route::post('/users/{user}/permissions/attach', [UserPermissionController::class, 'attach'])->name('user.permissions.attach');
    Route::post('/users/{user}/permissions/detach', [UserPermissionController::class, 'detach'])->name('user.permissions.detach');

    
    Route::get('/users/{user}/permissions/modal', [UserPermissionController::class, 'getPermissionsForModal'])
    ->name('users.permissions.modal');


    // Роли
    Route::get('/roles', [RoleController::class, 'index'])->name('roles');
    Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
    Route::get('/roles/{role}', [RoleController::class, 'show'])->name('roles.show');
    Route::delete('/roles/{role}', [RoleController::class, 'delete'])->name('roles.delete');


    // полномочия роли
    Route::get('/roles/{role}/permissions/create', [RolePermissionController::class, 'create'])->name('roles.permissions.create');
    Route::post('/roles/{role}/permissions/attach', [RolePermissionController::class, 'attach'])->name('roles.permissions.attach');
    Route::post('/roles/{role}/permissions/detach', [RolePermissionController::class, 'detach'])->name('roles.permissions.detach');

    Route::get('/roles/{role}/permissions/modal', [RolePermissionController::class, 'getPermissionsForModal'])
    ->name('roles.permissions.modal');


    // Полномочия
    Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions');
    Route::get('/permissions/create', [PermissionController::class, 'create'])->name('permissions.create');
    Route::post('/permissions', [PermissionController::class, 'store'])->name('permissions.store');
    Route::get('/permissions/{permission}/edit', [PermissionController::class, 'edit'])->name('permissions.edit');
    Route::put('/permissions/{permission}', [PermissionController::class, 'update'])->name('permissions.update');
    Route::get('/permissions/{permission}', [PermissionController::class, 'show'])->name('permissions.show');
    Route::delete('/permissions/{permission}', [PermissionController::class, 'delete'])->name('permissions.delete');
});


