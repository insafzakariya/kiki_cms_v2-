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

Route::group(['middleware' => ['auth'], 'prefix' => 'dashboard', 'namespace' => 'DashboardManage\Http\Controllers'], function()
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
   
    Route::get('/dailyrevenue', [
        'as' => 'index', 'uses' => 'DashboardController@dailyRevenue'
    ]);
    Route::get('data/dailyrevenue', [
        'as' => 'index', 'uses' => 'DashboardController@dailyRevenueData'
    ]);

    Route::get('/newsubscriberwithfreetrial', [
        'as' => 'index', 'uses' => 'DashboardController@newSubscriberWithFreeTrial'
    ]);
    Route::get('data/newsubscriberwithFreetrial', [
        'as' => 'index', 'uses' => 'DashboardController@newSubscriberWithFreeTrialData'
    ]);

    Route::get('/retentions-chart', [
        'as' => 'index', 'uses' => 'DashboardController@retentionChart'
    ]);
    Route::get('data/retention', [
        'as' => 'index', 'uses' => 'DashboardController@retentionChartData'
    ]);
    Route::get('/dailyactivity', [
        'as' => 'index', 'uses' => 'DashboardController@dailyActivity'
    ]);
    Route::get('data/dailyactivity', [
        'as' => 'index', 'uses' => 'DashboardController@dailyActivityData'
    ]);

    

    

});





