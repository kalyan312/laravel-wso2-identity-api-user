<?php

namespace Khbd\LaravelWso2IdentityApiUser\SDK\Wso2Idp\Traits;
use Illuminate\Support\Facades\Http;


trait RequestHandler
{
    private $client;
    private $response;
    private $isDebugEnabled = false;
    public function __construct(){
        $this->config = config('IdpUser');
        $this->isDebugEnabled = $this->config['idp_log'];
    }
    public function post(){

        try{
            $response = Http::get('http://exa9mples.com');
        }catch(\exception $exception){
            dd($exception->getMessage());
        }

        dd([
            $response->body(),
            $response->json() ,
            $response->object() ,
            $response->collect(),
            $response->status(),
            $response->ok(),
            $response->successful(),
            $response->failed() ,
            $response->serverError() ,
            $response->clientError(),
            $response->headers() ,
        ]);
    }

}
