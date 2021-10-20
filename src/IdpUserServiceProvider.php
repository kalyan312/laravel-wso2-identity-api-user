<?php

namespace Khbd\LaravelWso2IdentityApiUser;

use Khbd\LaravelWso2IdentityApiUser\Console\MakeIdpDriverCommand;
use Illuminate\Support\ServiceProvider;

class IdpUserServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/Config/wso2idp.php' => config_path('IdpUser.php'),
        ], 'IdpUser');

        $this->app->singleton(IdpUser::class, function () {
            return new IdpUser();
        });

        $this->app->alias(IdpUser::class, 'IdpUser');

        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeIdpDriverCommand::class,
            ]);
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/Config/IdpUser.php',
            'IdpUser'
        );
    }
}
