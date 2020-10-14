<?php

namespace MusicGenre;

use Illuminate\Support\ServiceProvider;

class MusicGenreManageServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../views', 'MusicGenre');
        require __DIR__ . '/Http/routes.php';
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('MusicGenre', function($app){
            return new MusicGenre;
        });
    }
}
