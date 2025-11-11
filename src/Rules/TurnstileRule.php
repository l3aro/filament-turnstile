<?php

namespace l3aro\FilamentTurnstile\Rules;

use Illuminate\Contracts\Validation\ValidationRule;
use Closure;
use l3aro\FilamentTurnstile\Facades\FilamentTurnstileFacade;

class TurnstileRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $response = FilamentTurnstileFacade::verify($value);

        if ($response->success) {
            return;
        }

        if (empty($response->errorCodes)) {
            $fail('filament-turnstile::errors.unexpected')->translate();
        }

        foreach ($response->errorCodes as $errorCode) {
            $fail('filament-turnstile::errors.' . $errorCode)->translate();
        }
    }
}
