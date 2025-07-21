<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\TestEmail;
use Illuminate\Support\Facades\Mail;

class SendEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-emails';

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
        Mail::to("abdyushevr@avangard.ru")->send(new TestEmail());
    }
}
