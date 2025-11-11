<?php

namespace l3aro\CloudflareTurnstile\Rules;

use Illuminate\Contracts\Validation\ValidationRule;
use Closure;
use l3aro\CloudflareTurnstile\Facades\CloudflareTurnstileFacade;

class TurnstileRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $response = CloudflareTurnstileFacade::verify($value);

        if ($response->success) {
            return;
        }

        if (empty($response->errorCodes)) {
            $fail('cloudflare-turnstile::errors.unexpected')->translate();
        }

        foreach ($response->errorCodes as $errorCode) {
            $fail('cloudflare-turnstile::errors.' . $errorCode)->translate();
        }
    }
}
