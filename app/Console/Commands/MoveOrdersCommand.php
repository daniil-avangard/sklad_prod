<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class MoveOrdersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:move';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Переносит неутвержденные заказы в новый месяц';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $oldOrders = Order::where('created_at', '<', Carbon::now())->get();
        $oldOrdersLength = count($oldOrders);
        $date = Carbon::now();

        foreach ($oldOrders as $oldOrder) {
            $oldOrder->created_at = $date;
            $oldOrder->save();
            // echo "Обновил дату у заказа: " . $oldOrder->id . "\r\n";
        }

        Log::info("Обновил дату для заказов. Месяц: {$date->month}. Кол-во обновленных заказов: {$oldOrdersLength}");
    }
}
