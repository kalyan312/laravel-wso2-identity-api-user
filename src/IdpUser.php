<?php

namespace Khbd\LaravelWso2IdentityApiUser;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Khbd\LaravelWso2IdentityApiUser\Models\SmsHistory;



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

    /**
     * Change the gateway on the fly.
     *
     * @param $gateway
     *
     * @return $this
     */
    public function gateway($gateway)
    {
        $this->gateway = $gateway;
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

    /**
     *map the gateway that will be used to send.
     */
    private function mapGateway()
    {
        $this->settings = $this->config['gateways'][$this->gateway];
        $class = $this->config['map'][$this->gateway];
        $this->object = new $class($this->settings);
    }

    /**
     * @param $recipient
     * @param $message
     * @param null $params
     *
     * @return mixed
     */
    public function request($recipient, $message, $params = null)
    {
        if($this->config['sms_activate'] == false) {
            return false;
        }
        if($this->config['sms_log']) {
            $this->beforeSend($recipient, $message, $params = null);
        }
        if(method_exists($this->object, 'fixNumber') && !$recipient = $this->object->fixNumber($recipient)){
            return false;
        }
        $object = $this->object->send($recipient, $message, $params);
        if($this->config['sms_log']) {
            $this->afterSend();
        }

        return $object;
    }

    /**
     * define when the a message is successfully sent.
     *
     * @return bool
     */
    public function is_successful()
    {
        return $this->object->is_successful();
    }

    /**
     * return api response getResponseBody
     *
     * @return object | array
     */
    public function getResponseBody()
    {
        return $this->object->getResponseBody();
    }

    /**
     * the message ID as received on the response.
     *
     * @return mixed
     */
    public function getMessageID()
    {
        return $this->object->getMessageID();
    }

    /**
     * @return mixed
     */
    public function getBalance()
    {
        return $this->object->getBalance();
    }

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function getDeliveryReports(Request $request)
    {
        return $this->object->getDeliveryReports($request);
    }

    private function beforeSend($recipient, $message, $params = null){
        try{
            $history = new SmsHistory();
            $history->mobile_number = $recipient;
            $history->message = $message;
            $history->gateway = $this->gateway;
            $history->created_at = now();
            $history->save();
            $this->smsRecord = $history;
        } catch (\Exception $exception){
            Log::debug("Faild to save sms message. " . $exception->getMessage());
        }
    }
    private function afterSend(){
        try{
            $status = 2;
            if($this->is_successful()){
                $status = 1;
            }

            if(is_object($this->smsRecord)){
                $this->smsRecord->status = $status;
                $this->smsRecord->sms_submitted_id = $this->getMessageID();
                $this->smsRecord->api_response = json_encode($this->getResponseBody());
                $this->smsRecord->save();
            }

        }catch (\Exception $exception){
            $exception->getMessage();
        }
    }
}
