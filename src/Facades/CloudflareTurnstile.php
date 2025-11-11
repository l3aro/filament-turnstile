<?php

namespace l3aro\CloudflareTurnstile\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \l3aro\CloudflareTurnstile\CloudflareTurnstile
 */
class CloudflareTurnstile extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \l3aro\CloudflareTurnstile\CloudflareTurnstile::class;
    }
}
