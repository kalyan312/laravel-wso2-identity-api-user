<?php

namespace Khbd\LaravelWso2IdentityApiUser\Interfaces;

use Illuminate\Http\Request;

interface IDPInterface
{
    /**
     * Construct the class with the relevant settings.
     *
     * @param $settings object
     */
    public function __construct($settings);

    /**
     * @param array $userInformation
     *
     * @return mixed
     */
    public function create(array $userInformation);


    /**
     * @param array $userInformation
     *
     * @return mixed
     */
    public function update(array $userInformation);

    /**
     * define when the a message is successfully sent.
     *
     * @return bool
     */
    public function isSuccessful();

    /**
     * the message ID as received on the response.
     *
     * @return mixed
     */
    public function getUserID();

    /**
     *  the message API response
     *
     * @return object
     */
    public function getResponseBody();

    /**
     * the message API response code
     *
     * @return int
     */
    public function getResponseCode();

    /**
     * the message API response message
     *
     * @return int
     */
    public function getResponseMessage();
}
