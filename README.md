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

| Key | Details |
| ------ | ------ |
| first_name | Update `givenName` |
| last_name | [Update `familyName` |
| email | Update  `emails` |
| mobile | Update `phoneNumbers` |
| user_type | Update `userType`|
| account_status | Update `accountStatus` |
| department | Update `department` |
| organization | Update `organization` |
| country | Update `country` |
| password | Update `password` |


>4. Delete single/bulk IDP User

```
        $userID = 'ID';
        $response = IdpUser()
        ->use('wso2idp')
        ->setPayload($userID)
        ->delete()
        ->get();

```
here - `$userID` can be single user ID or array of user ID. 




## Adding new Gateway

## .env Config

Currently Default SMS Gateway is [Bangladesh SMS](http://bangladeshsms.com/)

So .env config is following -
```bash
DEFAULT_IDP = 'wso2idp' #set default idp 

# add your wso2 idp information
WSO2_IDP_BASE_URL = 'http://wso2.com' # Note:: Do not include / after the base url
WSO2_IDP_USERNAME = 'admin'
WSO2_IDP_PASSWORD = 'admin'

IDP_ENABLED = true # true = if you want to enable functionality of idp
IDP_USER_DEBUG = true  # true = if you want to save log in file
```
>>  Note:: Do not include / after the base url

## Contributing

Suggestions, pull requests , bug reporting and code improvements are all welcome. Feel free.

## TODO

Write Tests

## Credits

- [Kalyan Halder](https://github.com/kalyan312)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
