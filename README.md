#  Laravel WSO2 Identity API User

This is a Laravel library to manage WSO2 IDP users.

## Installation

You can install the package via composer:

``` bash
composer require khbd/laravel-wso2-identity-api-user
```
## Laravel Usage
The package will register itself automatically.

Then publish the package configuration file

```bash
php artisan vendor:publish --provider=Khbd\LaravelWso2IdentityApiUser\IdpServiceProvider
```
or
```bash
php artisan vendor:publish --provider=Khbd\LaravelWso2IdentityApiUser\IdpServiceProvider  --tag="idpuser"
```
## Lumen Usage
For Lumen usage the service provider should be registered manually as follow in bootstrap/app.php:

```bash
$app->register(Khbd\LaravelWso2IdentityApiUser\IdpUserServiceProvider::class);

```
Copy <a href="https://github.com/tasmidur/laravel-wso2-identity-api-user/blob/main/src/Config/IdpUser.php">IdpUser</a> file to config directory. Then add the bellow text to the bootstrap/app.php:

```bash
$app->configure('IdpUser');
```
## Usage

Check the config file of all variables required, and then

```php
(new IdpUser())->create(array());
```
or using Facade

```php
IdpUser::create(array());
```

or using helper

```php
IdpUser()->create(array());
```

# Create you SDK
Run this command to create your own sdk class.
```
 php artisan make:idpdriver YourSDKName
```
Now add the class in config idpUser.php config file.

# API references
### Here all covered API references 

>1. Get Wso2 IDP User by ID

```
IdpUser()->setPayload('userID')->userInfo()->get();
```
or to get only response body

```
IdpUser()
->use('wso2idp')
->setPayload('userID')
->userInfo()
->onlyBody()
->get();
```
Here - 

-----

```use('yourSDK')``` `optional` set your custom SDK.

```onlyBody()``` `optional` return only response from IDP server/end API

----


```get()``` return response as `array`

```asObject()``` return response as `object`

```asJson()``` return response as `json`

----

>2. Create IDP user and get created user info

```
        $response = IdpUser()->setPayload([
        'first_name' => 'Kalyan',
        'last_name' => 'Kalyan',
        'username' => 'Kalyan4',
        'email' => 'Kalyan4@gmail.com',
        'mobile' => '01945602071',
        'user_type' => '2',
        'active' => true,
        'department' => 'Kalyan',
        ])->create()->get();
```

>3. Update User By User ID

_you can provide single field or multiple field at the same time_

```
        $response = IdpUser()->setPayload([
        'id' =>'UserID',
        'username' => 'Kalyan3',
        'account_status' => 1,
        'mobile' => '01945602071'
        ])->update()->get();
```
here `id` and `username` is mendatory. You can provide following field to update & create - 

| Key             | Data Type               |Details                 |
|-----------------|-------------------------|------------------------|
| first_name      | string                  |Update `givenName`      |
| last_name       | string                  |[Update `familyName`    |
| password        | string                  |Update `password`       |
| email           | email                   |Update  `emails`        |
| mobile          | string                  |Update `phoneNumbers`   |
| user_type       | integer                 |Update `userType`       |
| birthdate       | ISO_OFFSET_DATE_TIME [2018-10-03T07:24:14.772+03:00] |[Update `familyName`    |
| account_disable | boolean                 |Update `accountDisabled` |
| account_lock    | boolean                 |Update `accountLocked` |
| account_state   | string                  |Update `accountState`   |
| department      | string                  |Update `department`     |
| organization    | string                  |Update `organization`   |
| country         | string                  |Update `country`        |


## 4. Delete single/bulk IDP User

   _provide user `single id` to delete single user or provide `array of user id` to delete bulk user from `IDP`_

```
        $userID = 'ID';
        $response = IdpUser()
        ->use('wso2idp')
        ->setPayload($userID)
        ->delete()
        ->get();

```
*here - `$userID` can be single user ID or array of user ID.*

## 5. Reset password of users 
> user api, do not need admin permission

   _as a param pass a array of user crediantials like following example -_

```
   $response = IdpUser()->setPayload([
        'current_password' => 'kalyan111',
        'username'         => '01521212121',
        'new_password'     => 'newPass'
    ])->userResetPassword()->get();

```



## 5. find user list
> query users from IDP

   _as a param pass a array of filter like following example -_

```
    $response = IdpUser()->setPayload([
        'page' => 1,
        'count' => 10,
        'filter' => ''
    ])->findUsers()->get();

```


## Adding new Gateway

## .env Config

So .env config is following -
```bash
DEFAULT_IDP = 'wso2idp' #set default idp 

# add your wso2 idp information
WSO2_IDP_BASE_URL = 'http://wso2.com'
WSO2_IDP_USERNAME = 'admin'
WSO2_IDP_PASSWORD = 'admin'

IDP_ENABLED = true # true = if you want to enable functionality of idp
IDP_USER_DEBUG = true  # true = if you want to save log in file
```

## Contributing

Suggestions, pull requests , bug reporting and code improvements are all welcome. Feel free.

## TODO

Write Tests

## Credits

- [Kalyan Halder](https://github.com/kalyan312)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
