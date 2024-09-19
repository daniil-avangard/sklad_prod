<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserSettingsController;


Route::middleware('auth', 'admin')->group(function () {
    Route::get('user/settings', [UserSettingsController::class, 'index'])->name('user.settings');
    Route::put('user/settings', [UserSettingsController::class, 'update'])->name('user.settings.update');
    Route::put('user/settings/password', [UserSettingsController::class, 'updatePassword'])->name('user.settings.updatePassword');
});
