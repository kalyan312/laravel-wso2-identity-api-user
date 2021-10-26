<?php

namespace Khbd\LaravelWso2IdentityApiUser\Facades;

use Illuminate\Support\Facades\Facade;
use phpDocumentor\Reflection\Types\Mixed_;

/**
 * @method static use(string $sdk)
 * @method static setPayload($payload)
 *
 * @see \Khbd\LaravelWso2IdentityApiUser\IdpUser
 */
class IdpUser extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'IdpUser';
    }
}
