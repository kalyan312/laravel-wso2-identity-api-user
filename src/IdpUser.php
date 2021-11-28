<?php

return [


    /*
    |--------------------------------------------------------------------------
    | Enable IDP
    |--------------------------------------------------------------------------
    |
    | This value determines that should take sms record in db or not
    | You can switch to a different gateway at runtime.
    | set value true to Record Log
    |
    */

    'idp_activate' => env('IDP_ENABLED', true),


    /*
    |--------------------------------------------------------------------------
    | Enable debug log
    |--------------------------------------------------------------------------
    |
    | This value determines that should write log
    | set value true to Record Log
    |
    */

    'idp_log' => env('IDP_USER_DEBUG', true),

    /*
    |--------------------------------------------------------------------------
    | Default idp
    |--------------------------------------------------------------------------
    |
    | This value determines which of the following idp to use.
    | You can switch to a different idp at runtime.
    |
    */

    'default' => env('DEFAULT_IDP', 'wso2idp'),

    /*
    |--------------------------------------------------------------------------
    | List of Gateways
    |--------------------------------------------------------------------------
    |
    | These are the list of ip to use for this package.
    | You can change the name. Then you'll have to change
    | it in the map array too.
    |
    */

    'gateways' => [
        'wso2idp' => [
            'base_url' => env('WSO2_IDP_BASE_URL' ),
            'username' => env('WSO2_IDP_USERNAME' ),
            'password' => env('WSO2_IDP_PASSWORD')
        ],
    ],


    /*
    |--------------------------------------------------------------------------
    | Class Maps
    |--------------------------------------------------------------------------
    |
    | This is the array of Classes that maps to IDP above.
    | You can create your own driver if you like and add the
    | config in the drivers array and the class to use
    | here with the same name. You will have to implement
    | Khbd\LaravelWso2IdentityApiUser\Interfaces\IDPInterface in your IDP.
    |
    */

    'map' => [
        'wso2idp' => \Khbd\LaravelWso2IdentityApiUser\Idps\Wso2idp::class

    ],
];
