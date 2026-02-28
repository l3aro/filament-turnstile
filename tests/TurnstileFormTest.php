<?php

use l3aro\FilamentTurnstile\Enums\TurnstileSize;
use l3aro\FilamentTurnstile\Enums\TurnstileTheme;
use l3aro\FilamentTurnstile\Forms\Turnstile;

it('can convert TurnstileTheme enum to string', function () {
    $component = Turnstile::make('captcha')
        ->theme(TurnstileTheme::Dark);

    expect($component->getTheme())->toBe('dark');
});

it('can convert TurnstileSize enum to string', function () {
    $component = Turnstile::make('captcha')
        ->size(TurnstileSize::Compact);

    expect($component->getSize())->toBe('compact');
});

it('returns default theme when not set', function () {
    $component = Turnstile::make('captcha');

    expect($component->getTheme())->toBe('auto');
});

it('returns default size when not set', function () {
    $component = Turnstile::make('captcha');

    expect($component->getSize())->toBe('normal');
});

it('can accept string theme value', function () {
    $component = Turnstile::make('captcha')
        ->theme('light');

    expect($component->getTheme())->toBe('light');
});

it('can accept string size value', function () {
    $component = Turnstile::make('captcha')
        ->size('normal');

    expect($component->getSize())->toBe('normal');
});
