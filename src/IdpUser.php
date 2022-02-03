<?php

namespace Khbd\LaravelWso2IdentityApiUser;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use  Khbd\LaravelWso2IdentityApiUser\SDK\Wso2Idp\Wso2Idp;


class IdpUser
{
    /**
     * SMS Configuration.
     *
     * @var null|object
     */
    protected $config = null;

    /**
     * Sms Gateway Settings.
     *
     * @var null|object
     */
    protected $settings = null;

    /**
     * Sms Gateway Name.
     *
     * @var null|string
     */
    protected $gateway = null;

    /**
     * @var object
     */
    protected $object = null;

    /**
     * @var array
     */
    protected $payload = null;


    /**
     * IDP constructor.
     *
     * @throws \Exception
     */
    public function __construct()
    {
        $this->config = config('IdpUser');
        $this->gateway = $this->config['default'];
        $this->mapGateway();
    }

    public function __call($function, $args)
    {
            $payload = empty($args) ? [$this->payload]: $args;
            return call_user_func_array([$this->object, $function], $payload);
    }

    /**
     * Change the sdk on the fly.
     *
     * @param $sdk
     *
     * @return $this
     */
    public function use($sdk)
    {
        $this->gateway = $sdk;
        $this->mapGateway();

        return $this;
    }

    /**
     * set request payload on the fly.
     *
     * @param $payload
     *
     * @return $this
     */
    public function setPayload($payload)
    {
        $this->payload = $payload;
        return $this;
    }

    /****************
     * Private functions
     */

    /**
     *map the gateway that will be used to send.
     */
    private function mapGateway()
    {
        $this->settings = $this->config['idps'][$this->gateway];
        $this->settings["base_url"] = rtrim($this->settings["base_url"], "/");
        $this->settings['idp_log'] = $this->config['idp_log'];
        $class = $this->config['map'][$this->gateway];

        if(is_callable([$class, '__construct'], true, $callable_name)){
            $this->object = new $class($this->settings);
        }else{
            throw new \Exception("Unknown SDK. Make sure you have defined the sdk in the config file.", 422);
        }

    }
}
