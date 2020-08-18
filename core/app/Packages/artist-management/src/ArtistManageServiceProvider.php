<?php

namespace ArtistManage;

use Illuminate\Support\ServiceProvider;

class ArtistManageServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../views', 'ArtistManage');
        require __DIR__ . '/Http/routes.php';
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('ArtistManage', function($app){
            return new ArtistManage;
        });
    }
}
