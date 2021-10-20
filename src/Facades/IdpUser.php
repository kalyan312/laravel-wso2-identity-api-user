<?php

namespace Khbd\LaravelWso2IdentityApiUser\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static gateway(string $gateway)
 * @method static send(string $recipient, string $message, $params = null)
 * @method static bool is_successful()
 * @method static getMessageID()
 * @method static getBalance()
 * @method static object getDeliveryReports(\Illuminate\Http\Request $request)
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
