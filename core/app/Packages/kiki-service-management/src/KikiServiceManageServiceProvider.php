<?php

namespace KikiServiceManage;

use Illuminate\Support\ServiceProvider;

class KikiServiceManageServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../views', 'KikiServiceManage');
        require __DIR__ . '/Http/routes.php';
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('KikiServiceManage', function($app){
            return new KikiServiceManage;
        });
    }
}
