<?php

namespace ProgrammeSliderManage;

use Illuminate\Support\ServiceProvider;

class ProgrammeSliderManageServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../views', 'ProgrammeSliderManage');
        require __DIR__ . '/Http/routes.php';
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('ProgrammeSliderManage', function($app){
            return new ProgrammeSliderManage;
        });
    }
}
