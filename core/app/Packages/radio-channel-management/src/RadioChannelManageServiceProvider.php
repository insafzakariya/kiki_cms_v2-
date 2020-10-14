<?php

namespace RadioChannel;

use Illuminate\Support\ServiceProvider;

class RadioChannelManageServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../views', 'RadioChannel');
        require __DIR__ . '/Http/routes.php';
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('RadioChannel', function($app){
            return new RadioChannel;
        });
    }
}
