<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Redis;

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
        echo "vibhore is saying hello world by making his own command\n";
        Redis::set("NewRafael", "Hello world");
        $v = Redis::get("NewRafael");
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
