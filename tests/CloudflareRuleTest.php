<?php

use Illuminate\Support\Facades\Validator;
use l3aro\CloudflareTurnstile\Rules\TurnstileRule;

it('can validate cloudflare turnstile', function () {
    config()->set('cloudflare-turnstile.key', '1x00000000000000000000AA');
    config()->set('cloudflare-turnstile.secret', '1x0000000000000000000000000000000AA');

    $validator = Validator::make([
        'turnstile' => 'XXXX.DUMMY.TOKEN.XXXX',
    ], [
        'turnstile' => new TurnstileRule(),
    ]);

    expect($validator->passes())->toBeTrue();
});

it('can block cloudflare turnstile with invalid token', function () {
    config()->set('cloudflare-turnstile.key', '2x00000000000000000000AB');
    config()->set('cloudflare-turnstile.secret', '2x0000000000000000000000000000000AA');

    $validator = Validator::make([
        'turnstile' => 'XXXX.DUMMY.TOKEN.XXXX',
    ], [
        'turnstile' => new TurnstileRule(),
    ]);

    expect($validator->fails())->toBeTrue();
});
