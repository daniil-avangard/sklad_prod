<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

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

        foreach ($oldOrders as $oldOrder) {
            $oldOrder->created_at = Carbon::now();
            $oldOrder->save();
            echo "Обновил дату у заказа: " . $oldOrder->id . "\r\n";
        }

        $this->info("Обновил дату у заказов.");
    }
}
