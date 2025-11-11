<?php

namespace l3aro\CloudflareTurnstile\Rules;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;
use Closure;

class TurnstileRule implements ValidationRule
{
    protected ?string $url = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $response = Http::retry(3, 10)
            ->asJson()
            ->acceptJson()
            ->post($this->url, [
                'secret' => config('cloudflare-turnstile.secret'),
                'response' => $value,
            ]);

        if ($response->ok()) {
            return;
        }

        $errorCodes = $response->json('error-codes');

        if (!count($errorCodes)) {
            $fail('cloudflare-turnstile::errors.unexpected')->translate();
        }

        foreach ($errorCodes as $errorCode) {
            $fail('cloudflare-turnstile::errors.' . $errorCode)->translate();
        }
    }
}
