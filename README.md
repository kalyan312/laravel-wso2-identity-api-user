#  Laravel WSO2 Identity API User

This is a Laravel library to manage WSO2 IDP users.

## Installation

You can install the package via composer:

``` bash
composer require khbd/laravel-wso2-identity-api-user
```
The package will register itself automatically.

Then publish the package configuration file

```bash
php artisan vendor:publish --provider=Khbd\LaravelWso2IdentityApiUser\IdpServiceProvider
```
or
```bash
php artisan vendor:publish --provider=Khbd\LaravelWso2IdentityApiUser\IdpServiceProvider  --tag="idpuser"
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

## Adding new Gateway

## .env Config

Currently Default SMS Gateway is [Bangladesh SMS](http://bangladeshsms.com/)

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
