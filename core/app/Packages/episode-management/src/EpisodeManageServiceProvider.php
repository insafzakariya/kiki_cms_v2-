<?php

namespace EpisodeManage;

use Illuminate\Support\ServiceProvider;

class EpisodeManageServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../views', 'EpisodeManage');
        require __DIR__ . '/Http/routes.php';
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('EpisodeManage', function($app){
            return new EpisodeManage;
        });
    }
}
