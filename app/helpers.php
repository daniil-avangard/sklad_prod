<?php

use Illuminate\Support\Facades\Route;

if(!function_exists('is_active')){
    function is_active(string $name, string $active = 'active'): string
    {
        return Route::is($name) ? $active : '';
    }
}