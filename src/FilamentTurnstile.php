<?php

namespace l3aro\FilamentTurnstile;

use Illuminate\Support\Facades\Http;
use Throwable;

class FilamentTurnstile
{
    protected static ?string $url = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';

    public function verify(string $responseCode): FilamentTurnstileResponse
    {
        try {
            $response = Http::retry(3, 10)
                ->asJson()
                ->acceptJson()
                ->post(self::$url, [
                    'secret' => config('filament-turnstile.secret'),
                    'response' => $responseCode,
                ]);
        } catch (Throwable) {
            // Http::retry() throws once the attempts are exhausted, so an outage at
            // Cloudflare would otherwise surface as a 500 on the form. Fail closed
            // instead: the submission is rejected, but the page stays up.
            return FilamentTurnstileResponse::make(false, null);
        }

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
