<?php

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use l3aro\FilamentTurnstile\Facades\FilamentTurnstileFacade;

it('can verify cloudflare turnstile', function () {
    config()->set('filament-turnstile.key', '1x00000000000000000000AA');
    config()->set('filament-turnstile.secret', '1x0000000000000000000000000000000AA');

    expect(FilamentTurnstileFacade::verify('XXXX.DUMMY.TOKEN.XXXX')->success)->toBeTrue();
});

it('can verify cloudflare turnstile with fails response', function () {
    config()->set('filament-turnstile.key', '2x00000000000000000000AB');
    config()->set('filament-turnstile.secret', '2x0000000000000000000000000000000AA');

    expect(FilamentTurnstileFacade::verify('XXXX.DUMMY.TOKEN.XXXX')->success)->toBeFalse();
});

it('reports a failure when cloudflare cannot be reached', function () {
    config()->set('filament-turnstile.secret', '1x0000000000000000000000000000000AA');

    Http::fake(fn() => throw new ConnectionException('Connection timed out'));

    $response = FilamentTurnstileFacade::verify('XXXX.DUMMY.TOKEN.XXXX');

    expect($response->success)->toBeFalse()
        ->and($response->errorCodes)->toBeNull();
});

it('reports a failure when cloudflare keeps answering with a server error', function () {
    config()->set('filament-turnstile.secret', '1x0000000000000000000000000000000AA');

    Http::fake(fn() => Http::response('', 500));

    $response = FilamentTurnstileFacade::verify('XXXX.DUMMY.TOKEN.XXXX');

    expect($response->success)->toBeFalse()
        ->and($response->errorCodes)->toBeNull();
});
