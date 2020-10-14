<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Schema;
use Validator;
use Event;
use DB;
use Log;
class AppServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
                // Event::listen('illuminate.query', function($query)
                // {
                //     dump($query);
                // });
                DB::listen(function($query) {
                Log::info(
                        $query
                );
                });
        // Validator::extend('recaptcha','App\\Validators\\ReCaptcha@validate');

        // Validator::extend(
        //     'cus_numeric',
        //     function ($attribute, $value, $parameters)
        //     {
        //         if(isset($value[0])){
        //             return preg_match('/\d/', $value[0]) === 1;
        //         }
        //         return false;

        //     }
        // );
        // Validator::extend(
        //     'cus_min',
        //     function ($attribute, $value, $parameters)
        //     {

        //         if(isset($parameters[0]) && isset($value[0])){
        //             return $parameters[0] <= $value[0];
        //         }
        //         return false;

        //     }
        // );
        // Validator::extend(
        //     'cus_max',
        //     function ($attribute, $value, $parameters)
        //     {
        //         if(isset($parameters[0]) && isset($value[0])){
        //             return $parameters[0] >= $value[0];
        //         }
        //         return false;

        //     }
        // );
        // Validator::extend(
        //     'required_null',
        //     function ($attribute, $value, $parameters)
        //     {
        //         $valid  = false;
        //         if(is_array($value)){
        //             foreach ($value as $answer){
        //                 if (!empty($answer)){
        //                     $valid =true;
        //                 }
        //             }
        //             return $valid;
        //         }elseif(isset($value[0])){
        //             return !empty($value[0]);
        //         }
        //         return false;

        //     }
        // );
	}

	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}

}
