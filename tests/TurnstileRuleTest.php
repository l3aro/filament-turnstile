<?php

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use l3aro\FilamentTurnstile\Rules\TurnstileRule;

it('can validate cloudflare turnstile', function () {
    config()->set('filament-turnstile.key', '1x00000000000000000000AA');
    config()->set('filament-turnstile.secret', '1x0000000000000000000000000000000AA');

    $validator = Validator::make([
        'turnstile' => 'XXXX.DUMMY.TOKEN.XXXX',
    ], [
        'turnstile' => new TurnstileRule(),
    ]);

    expect($validator->passes())->toBeTrue();
});

it('can block cloudflare turnstile with invalid token', function () {
    config()->set('filament-turnstile.key', '2x00000000000000000000AB');
    config()->set('filament-turnstile.secret', '2x0000000000000000000000000000000AA');

    $validator = Validator::make([
        'turnstile' => 'XXXX.DUMMY.TOKEN.XXXX',
    ], [
        'turnstile' => new TurnstileRule(),
    ]);

    expect($validator->fails())->toBeTrue();
});

it('fails validation when the verification carries no error codes', function () {
    config()->set('filament-turnstile.secret', '1x0000000000000000000000000000000AA');

    Http::fake(fn() => Http::response(['success' => false]));

    $validator = Validator::make([
        'turnstile' => 'XXXX.DUMMY.TOKEN.XXXX',
    ], [
        'turnstile' => new TurnstileRule(),
    ]);

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->first('turnstile'))->toBe(__('filament-turnstile::errors.unexpected'));
});

it('fails validation when cloudflare cannot be reached', function () {
    config()->set('filament-turnstile.secret', '1x0000000000000000000000000000000AA');

    Http::fake(fn() => throw new ConnectionException('Connection timed out'));

    $validator = Validator::make([
        'turnstile' => 'XXXX.DUMMY.TOKEN.XXXX',
    ], [
        'turnstile' => new TurnstileRule(),
    ]);

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->first('turnstile'))->toBe(__('filament-turnstile::errors.unexpected'));
});

it('still reports every error code cloudflare sends', function () {
    config()->set('filament-turnstile.secret', '1x0000000000000000000000000000000AA');

    Http::fake(fn() => Http::response([
        'success' => false,
        'error-codes' => ['invalid-input-response', 'timeout-or-duplicate'],
    ]));

    $validator = Validator::make([
        'turnstile' => 'XXXX.DUMMY.TOKEN.XXXX',
    ], [
        'turnstile' => new TurnstileRule(),
    ]);

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->get('turnstile'))->toBe([
            __('filament-turnstile::errors.invalid-input-response'),
            __('filament-turnstile::errors.timeout-or-duplicate'),
        ]);
});
