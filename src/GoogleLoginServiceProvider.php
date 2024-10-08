<?php

namespace FmcExample\GoogleLogin;

use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\SocialiteServiceProvider;

class GoogleLoginServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->make('FmcExample\GoogleLogin\Http\Controllers\AuthController');
        $this->mergeConfigFrom(
            __DIR__.'/config/config.php', 'config'
        );

    }
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes/api.php');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->publishes([
            __DIR__.'/config/config.php' => config_path('config.php'),
        ]);
    }
}
