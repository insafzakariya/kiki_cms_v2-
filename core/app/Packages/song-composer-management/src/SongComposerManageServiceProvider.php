<?php

namespace SongComposerManage;

use Illuminate\Support\ServiceProvider;

class SongComposerManageServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../views', 'SongComposerManage');
        require __DIR__ . '/Http/routes.php';
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('SongComposerManage', function($app){
            return new SongComposerManage;
        });
    }
}
