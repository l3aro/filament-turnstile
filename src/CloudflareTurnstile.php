<?php

namespace l3aro\CloudflareTurnstile;

use Illuminate\Support\Facades\Http;

class CloudflareTurnstile
{
    protected static ?string $url = 'https://challenges.cloudflare.com/turnstile/v0/siteverify'; //

    public static function verify(string $responseCode): CloudflareTurnstileResponse
    {
        $response = Http::retry(3, 10)
            ->asJson()
            ->acceptJson()
            ->post(self::$url, [
                'secret' => config('cloudflare-turnstile.secret'),
                'response' => $responseCode,
            ]);

        return CloudflareTurnstileResponse::make(
            $response->ok() && $response->json('success'),
            $response->json('error-codes'),
        );
    }
}
