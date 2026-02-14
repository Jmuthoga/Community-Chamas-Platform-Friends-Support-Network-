<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class MpesaService
{
    public function accessToken()
    {
        $response = Http::withBasicAuth(
            config('mpesa.consumer_key'),
            config('mpesa.consumer_secret')
        )->get(config('mpesa.oauth_url'));

        return $response->json()['access_token'];
    }

    public function stkPush($phone, $amount, $accountReference, $description)
    {
        $timestamp = now()->format('YmdHis');

        $password = base64_encode(
            config('mpesa.shortcode')
                . config('mpesa.passkey')
                . $timestamp
        );

        $token = $this->accessToken();

        return Http::withToken($token)
            ->post(config('mpesa.stk_url'), [
                "BusinessShortCode" => config('mpesa.shortcode'),
                "Password" => $password,
                "Timestamp" => $timestamp,
                "TransactionType" => "CustomerPayBillOnline",
                "Amount" => $amount,
                "PartyA" => $phone,
                "PartyB" => config('mpesa.shortcode'),
                "PhoneNumber" => $phone,
                "CallBackURL" => config('mpesa.stk_callback_url'),
                "AccountReference" => $accountReference,
                "TransactionDesc" => $description
            ])->json();
    }
}
