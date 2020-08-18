<?php

namespace LyricistManage;

use Illuminate\Support\ServiceProvider;

class LyricistManageServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../views', 'LyricistManage');
        require __DIR__ . '/Http/routes.php';
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('LyricistManage', function($app){
            return new LyricistManage;
        });
    }
}
