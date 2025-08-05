<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PhpInfoController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/phpinfo', [PhpInfoController::class, 'index']);
