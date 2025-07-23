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
            $status = "<img src='/assets/images/galka_svg.svg' alt='logo-large' class='logo-lg logo-light'>";
        } elseif ($value == 'no' || $value === false || $value === null) {
            $status = "<img src='/assets/images/krest_svg.svg' alt='logo-large' class='logo-lg logo-light'>";
        } else {
            $status = Operator::tryFrom($value)->name() ?? (string)$value;
        }

        return $status;
    }
}
