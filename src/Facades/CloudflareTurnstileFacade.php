<?php

namespace l3aro\CloudflareTurnstile\Facades;

use l3aro\CloudflareTurnstile\CloudflareTurnstileResponse;
use Illuminate\Support\Facades\Facade;

/**
 * @method static CloudflareTurnstileResponse verify(string $response)
 * @see \l3aro\CloudflareTurnstile\CloudflareTurnstile
 */
class CloudflareTurnstileFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \l3aro\CloudflareTurnstile\CloudflareTurnstile::class;
    }
}
