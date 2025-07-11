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

    /**
     * Create a new job instance.
     */
    public function __construct(Order $orderData, $messageData)
    {
        $this->orderData = $orderData;
        $this->messageData = $messageData;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $testUser = "abdyushevr@avangard.ru";
        $appUser1 = $this->orderData->user;
        try {
            Mail::to($testUser)->send(new OrderShipped($appUser1, $this->messageData));
        } catch (Throwable $e) {
            report($e);
        } 
    }
}
