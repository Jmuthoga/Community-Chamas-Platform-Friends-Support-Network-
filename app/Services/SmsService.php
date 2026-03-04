<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    protected string $apiUrl;
    protected string $apiKey;

    public function __construct()
    {
        // Example config (you can store these in .env)
        $this->apiUrl = config('services.sms.url'); // e.g., 'https://api.smsprovider.com/send'
        $this->apiKey = config('services.sms.key');
    }

    /**
     * Send SMS to a number
     *
     * @param string $phone
     * @param string $message
     * @return bool
     */
    public function send(string $phone, string $message): bool
    {
        try {
            $response = Http::post($this->apiUrl, [
                'api_key' => $this->apiKey,
                'to' => $phone,
                'message' => $message,
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('SMS sending failed: ' . $e->getMessage());
            return false;
        }
    }
}
