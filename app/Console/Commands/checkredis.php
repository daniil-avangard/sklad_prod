<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Redis;
use App\Models\Order;

class checkredis extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:checkredis';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
//        $orderStatus = StatusEnum::WAREHOUSE_START->value;
        $index = 1008;
        $order = Order::find($index);
//        dd($order);
        echo "Receiving data from Postgres  \n";
        Redis::set("NewOrder_" . $index, $order);
        $v = Redis::get("NewOrder_" . $index);
        echo $v;
//        $redis = Redis::connection();
//        $redis->set('name', 'Taylor');
//        $name = $redis->get('name');
//        echo $name;
//        Redis::command('set', [
//            'Hello', 'World!', 300
//        ]);
//        Redis::command('get', ['Hello']);
    }
}
