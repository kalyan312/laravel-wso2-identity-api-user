<?php

namespace Khbd\LaravelWso2IdentityApiUser\SDK\Wso2Idp;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\Response;
use function PHPUnit\Framework\isNull;

class IdpGlobal
{
    private $apiUrl;
    private $apiUsername;
    private $apiPassword;
    private $enabledDebug;

    public function __construct($apiUrl, $apiUsername, $apiPassword, $enabledDebug)
    {
        $this->apiUrl = $apiUrl;
        $this->apiUsername = $apiUsername;
        $this->apiPassword = $apiPassword;
        $this->enabledDebug = $enabledDebug;
    }

    public function getAPIUsername(){
        return $this->apiUsername;
    }
    public function getAPIPassword(){
        return $this->apiPassword;
    }
    public function isEnabledDebug(){
        return $this->enabledDebug;
    }

    public function endpointUserInfo($userID){
        return $this->apiUrl . '/scim2/Users/'.$userID;
    }

    public function endpointUserCreate(){
        return $this->apiUrl . '/scim2/Users';
    }

    public function endpointUserUpdate($userID){
        return $this->apiUrl . '/scim2/Users/' . $userID;
    }

    public function endpointUserPasswordReset(){
        return $this->apiUrl . '/scim2/Me';
    }

    public function endpointUserFiltering($args){
        $pageNo = $args['page'] ?? 1;
        $countPerPage = $args['count'] ?? 10;
        $filter = null;
        if(isset($args['filter'])){
            $filter .= '&filter=' . $args['filter'];
        }
        $startFrom = ($pageNo -1) * $countPerPage;

        return $this->apiUrl .'/scim2/Users?startIndex=' . $startFrom . '&count=' . $countPerPage.$filter;
    }

    /**
     * @param $message
     * @param array $data
     * @param int $code
     * @param false $status
     * @return array
     */
    public function response($message, $data = [], $code = 422, $status = false){
                return [
                  'status' => $status,
                  'code' => $code,
                  'message' => $message,
                  'data' => $data
                ];
    }
    /**
     * @param $message
     * @param array $data
     * @param int $code
     * @param false $status
     * @return array
     */
    public function prepareResponse(Response $response, array $data = [], $customMessage = null){
                $message = 'Operation Successful.';
                $responseData  = $data;

                if($response->serverError() || $response->clientError()){
                    $message = 'Operation Not Successful.';
                }
                if($response->serverError()){
                    $message = 'Internal Idp Server Error, Please chaeck your payload and request carefully.';
                }
                if($response->serverError() || $response->clientError()){

                    $responseArray =  $response->json();

                    if(isset($responseArray['detail']) && !empty($responseArray['detail'])){
                        $message = $responseArray['detail'];
                    }else if(isset($responseArray['scimType']) && !empty($responseArray['scimType'])){
                        $message = $responseArray['scimType'];
                    } else {
                        if(isset($responseArray['schemas']) && is_array($responseArray['schemas'])){
                            $message = implode(", ", $responseArray['schemas'] );
                        } else if (isset($responseArray['schemas']) && !is_array($responseArray['schemas'])){
                            $message = $responseArray['schemas'];
                        }
                    }
                    $this->logInfo($message, [
                        'body' => $response->body(),
                        'collect' => $response->collect(),
                        'status_code' => $response->status(),
                        'is_response_200' => $response->ok(),
                        'is_successful' => $response->successful(),
                        'is_failed' => $response->failed() ,
                        'is_serverError' => $response->serverError() ,
                        'is_clientErro' => $response->clientError(),
                        'headers' => $response->headers(),
                    ]);
                }

                if(!empty($customMessage)){
                    $message = $customMessage;
                }

                if(empty($data) && !empty($response->json())){
                    $responseData = $response->json();
                } else if(!empty($data) && !empty($response->json())){
                    $responseData = array_merge($data, $response->json());
                }

                return [
                  'status' => $response->successful(),
                  'code' => $response->status(),
                  'message' => $message,
                  'data' => $responseData
                ];
    }

    public function logInfo($message, $array = []){
        if($this->isEnabledDebug())
            Log::debug("IDP Log:: ".$message, (array) $array);
    }

    public function prepareUserInfoToBeCreated($userinfo)
    {
        $first_name = $userinfo['first_name'] ?? null;
        $last_name = $userinfo['last_name'] ?? null;
        $username = $userinfo['username'] ?? null;
        $email = $userinfo['email'] ?? null;
        $mobile = $userinfo['mobile'] ?? null;
        $user_type = $userinfo['user_type'] ?? null;
        $active = $userinfo['active'] ?? true;
        $accountState = $userinfo['account_state'] ?? 'UNLOCKED';
        $department = $userinfo['department'] ?? null;
        $organization = $userinfo['organization'] ?? null;
        $country = $userinfo['country'] ?? 'Bangladesh';
        $password = $userinfo['password'] ?? '12345678';
        $birthDate = $userinfo['birthdate'] ?? null;
        $isAccountDisabled = isset($userinfo['account_disable']) ? ($userinfo['account_disable'] == 1 ? true : false) : null;
        $isAccountLocked = isset($userinfo['account_lock']) ? ($userinfo['account_lock'] == 1 ? true : false) : null;


        if(empty($first_name) || empty($last_name) || empty($username) || empty($email) || empty($mobile) || empty($user_type) || empty($active)){
            $this->logInfo("missing necessary user property. Provided user Info - ", (array) $userinfo);
            throw new \Exception("missing necessary user property", 422);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->logInfo("Invalid provided email. Provided user Info - ", (array) $userinfo);
            throw new \Exception("Invalid provided email", 422);
        }
        $payload = [
            'schemas' => [
                "urn:ietf:params:scim:schemas:core:2.0:User",
                "urn:ietf:params:scim:schemas:extension:enterprise:2.0:User"
            ],
            'name' => [
                'familyName' => $last_name,
                'givenName' => $first_name,
            ],

            'userName' => $username,
            'active' => $active,
            'password' => $password,
            'userType' => $user_type,
            'urn:ietf:params:scim:schemas:extension:enterprise:2.0:User' => [
                'accountDisabled' => $isAccountDisabled,
                'accountLocked' => $isAccountLocked,
                'department' => $department,
                'country' => $country,
                'organization' => $organization
            ],
            'emails' => [
                0 => $email
            ],
            'phoneNumbers' =>  [
                [
                    "value" => $mobile,
                    'type' => 'mobile',
                    "primary" => "false"
                ]
            ]
        ];

        if(!empty($birthDate)){
            $payload['urn:ietf:params:scim:schemas:extension:enterprise:2.0:User']['dateOfBirth'] = $birthDate;
        }

        return $payload;
    }

    public function preparePasswordResetSchema($userCrediantialInfo)
    {
        $currentPassword = $userCrediantialInfo['current_password'] ?? null;
        $username = $userCrediantialInfo['username'] ?? null;
        $newPassword = $userCrediantialInfo['new_password'] ?? null;


        if(empty($currentPassword) || empty($username) || empty($newPassword)){
            $this->logInfo("missing necessary user crediantial. Provided user crediantial - ", (array) $userCrediantialInfo);
            throw new \Exception("username or password or new password missing", 422);
        }

        $payload = [
            'schemas' => [
                "urn:ietf:params:scim:api:messages:2.0:PatchOp"
            ],
            'Operations' => [
                [
                    "op" => "add",
                    "value" => [
                        "password" => $newPassword
                    ]
                ]
            ]
        ];

        return $payload;
    }

    public function prepareUserInfoToBeUpdated($userinfo)
    {
        $ID = $userinfo['id'] ?? null;
        $first_name = $userinfo['first_name'] ?? null;
        $last_name = $userinfo['last_name'] ?? null;
        $username = $userinfo['username'] ?? null;
        $email = $userinfo['email'] ?? null;
        $mobile = $userinfo['mobile'] ?? null;
        $user_type = $userinfo['user_type'] ?? null;
        $active = $userinfo['active'] ?? null;
        $account_status = $userinfo['account_status'] ?? null;
        $department = $userinfo['department'] ?? null;
        $organization = $userinfo['organization'] ?? null;
        $country = $userinfo['country'] ?? null;
        $password = $userinfo['password'] ?? null;
        $isAccountDisabled = isset($userinfo['account_disable']) ? ($userinfo['account_disable'] == 1 ? true : false) : null;
        $isAccountLocked = isset($userinfo['account_lock']) ? ($userinfo['account_lock'] == 1 ? true : false) : null;
        $birthDate = $userinfo['birthdate'] ?? null;

        if(empty($ID)){
            $this->logInfo("missing user ID. Provided user Info - ", (array) $userinfo);
            throw new \Exception("ID is mendatory.", 422);
        }
        if(empty($username)){
            $this->logInfo("missing username. Provided user Info - ", (array) $userinfo);
            throw new \Exception("username is mendatory.", 422);
        }

        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->logInfo("Invalid provided email. Provided user Info - ", (array) $userinfo);
            throw new \Exception("Invalid provided email", 422);
        }

        // "add" and "replace"
        $payload = [];

        $payload['schemas'] = [
            "urn:ietf:params:scim:api:messages:2.0:PatchOp"
        ];
        $values = [];

        if(!empty($first_name)){
            $values['name']['givenName'] = $first_name;
        }

        if(!empty($last_name)){
            $values['name']['familyName'] = $last_name;
        }

        if($isAccountDisabled !== null){
            # more about account state https://is.docs.wso2.com/en/latest/learn/pending-account-status/
            # also another way to set payload curl -v -k --user admin:admin -X PATCH -d '{"schemas":["urn:ietf:params:scim:api:messages:2.0:PatchOp"],"Operations":[{"op":"replace","path":"urn:ietf:params:scim:schemas:extension:enterprise:2.0:User:accountLocked", "value": true}]}' --header "Content-Type:application/json" https://identity.com/scim2/Users/id
           $values['urn:ietf:params:scim:schemas:extension:enterprise:2.0:User']['accountDisabled'] =  $isAccountDisabled;
        }
        if($isAccountLocked !== null){
            # more about account state https://is.docs.wso2.com/en/latest/learn/pending-account-status/
           $values['urn:ietf:params:scim:schemas:extension:enterprise:2.0:User']['accountLocked'] =  $isAccountLocked;
        }
        if(!empty($organization)){
            $values['urn:ietf:params:scim:schemas:extension:enterprise:2.0:User']['organization'] =  $organization;
        }
        if(!empty($department)){
            $values['urn:ietf:params:scim:schemas:extension:enterprise:2.0:User']['department'] =  $department;
        }
        if(!empty($country)){
            $values['urn:ietf:params:scim:schemas:extension:enterprise:2.0:User']['country']= $country;
        }
        if(!empty($birthDate)){
            $values['urn:ietf:params:scim:schemas:extension:enterprise:2.0:User']['dateOfBirth']= $birthDate;
        }
        if(!empty($password)){
            $values['password'] =  $password;
        }

        if(!empty($user_type)){
            $values['userType'] = $user_type;
        }

        if(!empty($active)){
            $values['active'] = (string) $active;
        }

        if(!empty($email)){
            $values['emails'] =  [
                0 => $email
            ];
        }
        if(!empty($mobile)){
            $values['phoneNumbers'] =  [
               [
                   "value" => $mobile,
                   'type' => 'mobile',
                    "primary" => "false"
               ]
            ];
        }
        if(empty($values)){
            $this->logInfo("Update value can not be empty, please add something to update");
            throw new \Exception("Empty value provided, Nothing to update.", 422);
        }
        $payload['Operations'][] = [
            "op" => "replace",
            "value" => $values
        ];

        Log::debug("update payload", $payload);

        return $payload;
    }

}
