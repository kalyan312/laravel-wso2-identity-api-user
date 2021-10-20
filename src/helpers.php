<?php

if (!function_exists('IdpUser')) {

    /**
     * @return \Khbd\LaravelWso2IdentityApiUser\IdpUser
     *@throws Exception
     *
     */
    function IdpUser()
    {
        return new \Khbd\LaravelWso2IdentityApiUser\IdpUser();
    }
}
