<?php

namespace Khbd\LaravelWso2IdentityApiUser\SDK\Wso2Idp;

class Wso2Idp
{
    private $apiUrl;
    private $userName;
    private $apiKey;
    private $from;

    public function __construct($apiUrl, $userName, $apiKey, $from)
    {
        $this->apiUrl = $apiUrl;
        $this->userName = $userName;
        $this->apiKey = $apiKey;
        $this->from = $from;
    }


    public function create($userData){
     dd('hi');
    }
}
