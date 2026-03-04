<?php

namespace App\Jobs;

use App\Services\SmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected ?string $phone; // allow null
    protected string $message;

    public function __construct(?string $phone, string $message)
    {
        $this->phone = $phone;
        $this->message = $message;
    }

    public function handle(SmsService $smsService)
    {
        if ($this->phone) { // only send if phone is not null
            $smsService->send($this->phone, $this->message);
        }
    }
}