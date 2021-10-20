<?php

namespace Khbd\LaravelWso2IdentityApiUser\Idps;

use Khbd\LaravelWso2IdentityApiUser\Interfaces\IDPInterface;
use Khbd\LaravelWso2IdentityApiUser\SDK\Wso2Idp\Wso2Idp as IDPGateway;
use Illuminate\Http\Request;

class Wso2idp implements IDPInterface
{
    /**
     * @var array
     */
    protected $settings;

    /**
     * @var string
     */
    protected $user_id;
    /**
     * @var bool
     */
    protected $is_success;

    /**
     * @var int
     */
    protected $response_code;

    /**
     * @var mixed
     */
    protected $message;

    /**
     * @var object
     */
    public $data;

    /**
     * @var object | array
     */
    public $response;

    /**
     * @param $settings
     *
     * @throws \Exception
     */
    public function __construct($settings)
    {
        // initiate settings (username, api_key, etc)

        $this->settings = (object) $settings;
    }

    /**
     * @param $recipient
     * @param $message
     * @param null $params
     *
     * @return object
     */
    public function create(array $userInfo)
    {

        $AT = new IDPGateway($this->settings->base_url, $this->settings->username, $this->settings->api_key, $this->settings->from);
        $this->response = $AT->create();

        return $this;
    }


    /**
     * Update user
     * @param array $userInformation
     * @return mixed|void
     */
    public function update(array $userInformation)
    {

    }

    /**
     * initialize the is_success parameter.
     *
     * @return bool
     */
    public function isSuccessful(): bool
    {
        return $this->is_success;
    }

    /**
     * initialize the getResponseBody parameter.
     *
     * @return bool
     */
    public function getResponseBody()
    {
        return $this->response;
    }

    /**
     * assign the message ID as received on the response,auto generate if not available.
     *
     * @return mixed
     */
    public function getResponseMessage()
    {
        return $this->message;
    }

    public function getResponseCode()
    {
        $this->response_code;
    }

    public function getUserID()
    {
        return $this->user_id;
    }

    /**
     * auto generate if not available.
     */
    public function getBalance()
    {
        $AT = new IDPGateway($this->settings->base_url, $this->settings->username, $this->settings->api_key, $this->settings->from);
        return $AT->balance();
    }

    /**
     * @param Request $request
     *
     * @return object
     */
    public function getDeliveryReports(Request $request)
    {
        $status = $request->status;

        if ($status == 'Failed' || $status == 'Rejected') {
            $fs = $request->failureReason;
        } else {
            $fs = $status;
        }

        $data = [
            'status'       => $fs,
            'message_id'   => $request->id,
            'phone_number' => '',
        ];

        return (object) $data;
    }

    public function fixNumber($number){
       $validCheckPattern = "/^(?:\+88|01)?(?:\d{11}|\d{13})$/";
       if(preg_match($validCheckPattern, $number)){
           if(preg_match('/^(?:01)\d+$/', $number)){
               $number = '+88' . $number;
           }

           return $number;
       }

       return false;
    }
}
