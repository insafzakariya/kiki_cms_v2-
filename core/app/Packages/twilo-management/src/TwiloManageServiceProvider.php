<?php

namespace TwiloManage;

use Illuminate\Support\ServiceProvider;

class TwiloManageServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../views', 'TwiloManage');
        require __DIR__ . '/Http/routes.php';
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('TwiloManage', function($app){
            return new TwiloManage;
        });
    }
}
