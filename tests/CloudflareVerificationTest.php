<?php

use l3aro\CloudflareTurnstile\Facades\CloudflareTurnstileFacade;

it('can verify cloudflare turnstile', function () {
    config()->set('cloudflare-turnstile.key', '1x00000000000000000000AA');
    config()->set('cloudflare-turnstile.secret', '1x0000000000000000000000000000000AA');

    expect(CloudflareTurnstileFacade::verify('XXXX.DUMMY.TOKEN.XXXX')->success)->toBeTrue();
});

it('can verify cloudflare turnstile with fails response', function () {
    config()->set('cloudflare-turnstile.key', '2x00000000000000000000AB');
    config()->set('cloudflare-turnstile.secret', '2x0000000000000000000000000000000AA');

    expect(CloudflareTurnstileFacade::verify('XXXX.DUMMY.TOKEN.XXXX')->success)->toBeFalse();
});
