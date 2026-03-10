<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    protected string $apiUrl;
    protected string $apiKey;
    protected string $username;
    protected string $sender;
    protected bool $sandbox;

    public function __construct()
    {
        $this->sandbox  = env('SMS_SANDBOX', true);
        $this->apiUrl   = 'https://api.sandbox.africastalking.com/version1/messaging';
        if (!$this->sandbox) {
            $this->apiUrl = 'https://api.africastalking.com/version1/messaging';
        }
        $this->apiKey   = env('SMS_API_KEY');
        $this->username = env('SMS_USERNAME');
        $this->sender   = $this->sandbox ? 'Sandbox' : env('SMS_SENDER');
    }

    public function send(string $phone, string $message): bool
    {
        try {
            if (!str_starts_with($phone, '+')) {
                $phone = '+' . $phone;
            }

            // Build the form data (x-www-form-urlencoded)
            $formData = [
                'username' => $this->username,
                'to'       => $phone,
                'message'  => $message,
                'from'     => $this->sender,
            ];

            Log::info('SMS REQUEST DATA', $formData);

            $response = Http::asForm() // <-- this sends data as application/x-www-form-urlencoded
                            ->withHeaders([
                                'apiKey' => $this->apiKey,
                                'Accept' => 'application/json',
                            ])
                            ->post($this->apiUrl, $formData);

            $body = $response->json();

            Log::info('SMS RESPONSE DATA', [
                'phone' => $phone,
                'body'  => $body,
            ]);

            if (!empty($body['SMSMessageData']['Recipients'][0]['statusCode'])) {
                $statusCode = $body['SMSMessageData']['Recipients'][0]['statusCode'];
                return in_array($statusCode, [100, 101]); // success codes
            }

            return false;

        } catch (\Exception $e) {
            Log::error('SMS EXCEPTION: '.$e->getMessage());
            return false;
        }
    }
}