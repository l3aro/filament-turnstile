<?php

namespace l3aro\FilamentTurnstile\Facades;

use l3aro\FilamentTurnstile\FilamentTurnstileResponse;
use Illuminate\Support\Facades\Facade;

/**
 * @method static FilamentTurnstileResponse verify(string $response)
 * @method static string getResetEventName()
 *
 * @see \l3aro\FilamentTurnstile\FilamentTurnstile
 */
class FilamentTurnstileFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \l3aro\FilamentTurnstile\FilamentTurnstile::class;
    }
}
