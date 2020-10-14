<?php

namespace PlaylistManage;

use Illuminate\Support\ServiceProvider;

class PlaylistManageServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../views', 'PlaylistManage');
        require __DIR__ . '/Http/routes.php';
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('PlaylistManage', function($app){
            return new PlaylistManage;
        });
    }
}
