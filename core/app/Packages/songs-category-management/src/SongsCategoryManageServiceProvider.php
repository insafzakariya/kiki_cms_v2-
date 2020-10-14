<?php

namespace SongsCategory;

use Illuminate\Support\ServiceProvider;

class SongsCategoryManageServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../views', 'SongsCategory');
        require __DIR__ . '/Http/routes.php';
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'SongsCategory', function ($app) {
                return new SongsCategory;
            }
        );
    }
}
