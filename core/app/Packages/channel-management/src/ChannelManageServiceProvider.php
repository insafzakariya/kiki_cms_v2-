<?php

namespace ChannelManage;

use Illuminate\Support\ServiceProvider;

class ChannelManageServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../views', 'ChannelManage');
        require __DIR__ . '/Http/routes.php';
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('ChannelManage', function($app){
            return new ChannelManage;
        });
    }
}
