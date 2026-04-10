<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class InfobipSms
{
    public function send(string $to, string $text): array
    {
        $baseUrl = rtrim((string) config('services.infobip.base_url'), '/');
        $apiKey  = trim((string) config('services.infobip.api_key'));
        $sender  = (string) config('services.infobip.sender', 'ServiceSMS');

//        dd([
//     'base_url' => config('services.infobip.base_url'),
//     'api_key_present' => !empty(config('services.infobip.api_key')),
//     'sender' => config('services.infobip.sender'),
//     'auth_header_preview' => 'App ' . substr((string) config('services.infobip.api_key'), 0, 6) . '...',
// ]);

        $response = Http::withHeaders([
            'Authorization' => 'App ' . $apiKey,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post($baseUrl . '/sms/3/messages', [
            'messages' => [
                [
                    'sender' => $sender,
                    'destinations' => [
                        ['to' => $to],
                    ],
                    'content' => [
                        'text' => $text,
                    ],
                ],
            ],
        ]);

        if (!$response->successful()) {
            throw new \Exception('Infobip SMS failed: ' . $response->body());
        }

        return $response->json();
    }
}