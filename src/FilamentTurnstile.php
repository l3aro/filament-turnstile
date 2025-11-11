<?php

namespace l3aro\FilamentTurnstile;

use Illuminate\Support\Facades\Http;

class FilamentTurnstile
{
    protected static ?string $url = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';

    public function verify(string $responseCode): FilamentTurnstileResponse
    {
        $response = Http::retry(3, 10)
            ->asJson()
            ->acceptJson()
            ->post(self::$url, [
                'secret' => config('filament-turnstile.secret'),
                'response' => $responseCode,
            ]);

        return FilamentTurnstileResponse::make(
            $response->ok() && $response->json('success'),
            $response->json('error-codes'),
        );
    }

    public function getResetEventName(): string
    {
        return config('filament-turnstile.reset_event');
    }
}
