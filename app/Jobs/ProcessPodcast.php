<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
//use Illuminate\Services\AudioProcessor;
use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderShipped;
use Throwable;

class ProcessPodcast implements ShouldQueue
{
    use Queueable;
    public $orderData;
    public $messageData;
    public $orderEmail;

    /**
     * Create a new job instance.
     */
    public function __construct(Order $orderData, $messageData)
    {
        $this->orderData = $orderData;
        $this->orderEmail = strval($orderData->user->email);
        $this->messageData = $messageData;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $testUser = strval(env('MAIL_MODE_STATUS')) == "dev" ? "abdyushevr@avangard.ru" : $this->orderEmail;
        $appUser1 = $this->orderData->user;
        try {
            Mail::to($testUser)->send(new OrderShipped($appUser1, $this->messageData));
        } catch (Throwable $e) {
            report($e);
        } 
    }
}
