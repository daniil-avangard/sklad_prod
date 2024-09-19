<?php

use Illuminate\Support\Facades\Route;
use App\Enum\Products\PointsSale\Operator;

if (!function_exists('is_active')) {
    function is_active(string $name, string $active = 'active'): string
    {
        return Route::is($name) ? $active : '';
    }
}

if (!function_exists('kko_express_check')) {
    function kko_express_check($value): string
    {
        if ($value instanceof Operator) {
            $value = $value->name;
        }

        if ($value == 'yes' || $value === true) {
            $status = "<i data-feather='check' style='color: green;'></i>";
        } elseif ($value == 'no' || $value === false) {
            $status = "<i data-feather='x' style='color: red;'></i>";
        } else {
            $status = Operator::tryFrom($value)->name() ?? (string)$value;
        }

        return $status;
    }
}
