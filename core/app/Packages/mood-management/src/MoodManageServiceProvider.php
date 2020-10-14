<?php

namespace MoodManage;

use Illuminate\Support\ServiceProvider;

class MoodManageServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../views', 'MoodManage');
        require __DIR__ . '/Http/routes.php';
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('MoodManage', function($app){
            return new MoodManage;
        });
    }
}
