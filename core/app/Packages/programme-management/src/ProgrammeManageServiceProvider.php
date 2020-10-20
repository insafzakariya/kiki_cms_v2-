<?php

namespace ProgrammeManage;

use Illuminate\Support\ServiceProvider;

class ProgrammeManageServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../views', 'ProgrammeManage');
        require __DIR__ . '/Http/routes.php';
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('ProgrammeManage', function($app){
            return new ProgrammeManage;
        });
    }
}
