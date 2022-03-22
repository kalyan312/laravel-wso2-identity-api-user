<?php

namespace Khbd\LaravelWso2IdentityApiUser\SDK\Wso2Idp;
use Illuminate\Support\Facades\Http;

class Wso2IdpUsers extends IdpGlobal
{
    public function __construct(){
        parent::__construct(...func_get_args());

    }

    public function create($userData){
        try {
            $payload = $this->prepareUserInfoToBeCreated($userData);

            $response = Http::withBasicAuth($this->getApiUsername(), $this->getAPIPassword())
                ->withHeaders([
                    'Content-Type' => 'application/json'
                ])
                ->withOptions([
                    'verify' => false
                ])
                ->post($this->endpointUserCreate(), $payload);
        } catch (\Exception $exception){
            $exceptionInfo =  $this->response($exception->getMessage(), $userData, $exception->getCode(), false);
            $this->logInfo($exception->getMessage(), $exceptionInfo);
            return $exceptionInfo;
        }

        return $this->prepareResponse($response);
    }

    public function update($userData){
        try {
            $payload = $this->prepareUserInfoToBeUpdated($userData);

            $response = Http::withBasicAuth($this->getApiUsername(), $this->getAPIPassword())
                ->withHeaders([
                    'Content-Type' => 'application/json'
                ])
                ->withOptions([
                    'verify' => false
                ])
                ->patch($this->endpointUserUpdate($userData['id']), $payload);
        } catch (\Exception $exception){
            $exceptionInfo =  $this->response($exception->getMessage(), $userData, $exception->getCode(), false);
            $this->logInfo($exception->getMessage(), $exceptionInfo);
            return $exceptionInfo;
        }

        return $this->prepareResponse($response);
    }


    public function userInfo($userID){
        try {
            if(empty($userID)){
                $this->logInfo("missing user ID.");
                throw new \Exception("ID is mendatory.", 422);
            }

            $response = Http::withBasicAuth($this->getApiUsername(), $this->getAPIPassword())
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/scim+json'
                ])
                ->withOptions([
                    'verify' => false
                ])
                ->get($this->endpointUserInfo($userID));
            return $this->prepareResponse($response);

        } catch (\Exception $exception){
            $exceptionInfo =  $this->response($exception->getMessage(), $userInfo, $exception->getCode(), false);
            $this->logInfo($exception->getMessage(), $exceptionInfo);
            return $exceptionInfo;
        }
    }

    public function userResetPassword($userCrediantialInfo){
        try {
            $payload = $this->preparePasswordResetSchema($userCrediantialInfo);
            $this->logInfo("payload", $payload);
            $response = Http::withBasicAuth($userCrediantialInfo['username'], $userCrediantialInfo['current_password'])
                ->withHeaders([
                    'Content-Type' => 'application/json'
                ])
                ->withOptions([
                    'verify' => false
                ])
                ->patch($this->endpointUserPasswordReset(), $payload);
        } catch (\Exception $exception){
            $exceptionInfo =  $this->response($exception->getMessage(), $userCrediantialInfo, $exception->getCode(), false);
            $this->logInfo($exception->getMessage(), $exceptionInfo);
            
            return $exceptionInfo;
        }
        return $this->prepareResponse($response, $userCrediantialInfo, ($response->status() == 401 ? "Username or password is incorrect. Please try with correct username or password." : null));
    }

    public function findUsers($filter){
        try {
            if(empty($filter)){
                $filter = [
                    'page' => 1,
                    'count' => 10,
                    'filter' => ''
                ];
            }
            if(isset($filter['filter']) && empty($filter['filter'])){
                unset($filter['filter']);
            }

            $response = Http::withBasicAuth($this->getApiUsername(), $this->getAPIPassword())
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/scim+json'
                ])
                ->withOptions([
                    'verify' => false
                ])
                ->get($this->endpointUserFiltering($filter));
            return $this->prepareResponse($response);

        } catch (\Exception $exception){
            $exceptionInfo =  $this->response($exception->getMessage(), $filter, $exception->getCode(), false);
            $this->logInfo($exception->getMessage(), $exceptionInfo);
            return $exceptionInfo;
        }
    }

    public function delete($userInfo){
        try {
            if(empty($userInfo)){
                $this->logInfo("missing user ID.");
                throw new \Exception("ID is mendatory.", 422);
            }
            if(is_array($userInfo)){
                $responseArr = [];
                $hasFailedResponse = false;
                $hasSuccessResponse = false;
                foreach ($userInfo as $ID){
                    $response = $this->deleteSingleUser($ID);
                    if(!$response->successful()){
                        $hasFailedResponse = true;
                    }else{
                        $hasSuccessResponse = true;
                    }
                    $tmpResponse = $this->prepareResponse($response);
                    $tmpResponse['data']['userID'] = $ID;
                    $responseArr[] = $tmpResponse;
                }
                return $this->response($hasFailedResponse ? "Action pertiaally or complaitly failed.":"Action successfully completed.", $responseArr, $hasSuccessResponse ? 200 : 206, $hasSuccessResponse ? true : false);
            }else{
                $response = $this->deleteSingleUser($userInfo);
                return $this->prepareResponse($response);
            }
        } catch (\Exception $exception){
            $exceptionInfo =  $this->response($exception->getMessage(), $userInfo, $exception->getCode(), false);
            $this->logInfo($exception->getMessage(), $exceptionInfo);
            return $exceptionInfo;
        }
    }

    private function deleteSingleUser($ID){
        $response = Http::withBasicAuth($this->getApiUsername(), $this->getAPIPassword())
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/scim+json'
            ])
            ->withOptions([
                'verify' => false
            ])
            ->delete($this->endpointUserUpdate($ID));
        return $response;
    }
}
