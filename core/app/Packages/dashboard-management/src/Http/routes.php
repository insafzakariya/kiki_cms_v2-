<?php
/**
 * DASHBOARD MANAGEMENT ROUTES
 *
 * @version 1.0.0
 * @author Insaf Zakariya <insaf.zak@gmail.com>
 * @copyright 2015 Insaf Zakariya
 */

/**
 * USER AUTHENTICATION MIDDLEWARE
 */

Route::group(['middleware' => [], 'prefix' => 'dashboard', 'namespace' => 'DashboardManage\Http\Controllers'], function()
{

    Route::get('/subscribe', [
        'as' => 'index', 'uses' => 'DashboardController@subsribe'
    ]);
    Route::get('data/subscribe', [
        'as' => 'index', 'uses' => 'DashboardController@subsribeData'
    ]);

    Route::get('/dailytransaction', [
        'as' => 'index', 'uses' => 'DashboardController@dailyTransaction'
    ]);
    Route::get('data/dailytransaction', [
        'as' => 'index', 'uses' => 'DashboardController@dailyTransactionData'
    ]);

    

    

});





