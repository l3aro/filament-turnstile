<?php

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
