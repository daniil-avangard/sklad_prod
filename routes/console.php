<?php

use App\Console\Commands\MoveOrdersCommand;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Переносит неутвержденные заказы в новый месяц
Schedule::command(MoveOrdersCommand::class)->monthly();
