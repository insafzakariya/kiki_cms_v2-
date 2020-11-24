<?php

namespace ScratchCardManage;

use Illuminate\Support\ServiceProvider;

class ScratchCardManageServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../views', 'ScratchCardManage');
        require __DIR__ . '/Http/routes.php';
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('ScratchCardManage', function($app){
            return new ScratchCardManage;
        });
    }
}
