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
        'as' => 'report', 'uses' => 'DashboardController@subsribe'
    ]);
    Route::get('data/subscribe', [
        'as' => 'report', 'uses' => 'DashboardController@subsribeData'
    ]);

    Route::get('/dailytransaction', [
        'as' => 'report', 'uses' => 'DashboardController@dailyTransaction'
    ]);
    Route::get('data/dailytransaction', [
        'as' => 'report', 'uses' => 'DashboardController@dailyTransactionData'
    ]);
   
    Route::get('/dailyrevenue', [
        'as' => 'report', 'uses' => 'DashboardController@dailyRevenue'
    ]);
    Route::get('data/dailyrevenue', [
        'as' => 'report', 'uses' => 'DashboardController@dailyRevenueData'
    ]);

    Route::get('/newsubscriberwithfreetrial', [
        'as' => 'report', 'uses' => 'DashboardController@newSubscriberWithFreeTrial'
    ]);
    Route::get('data/newsubscriberwithFreetrial', [
        'as' => 'report', 'uses' => 'DashboardController@newSubscriberWithFreeTrialData'
    ]);

    Route::get('/retentions-chart', [
        'as' => 'report', 'uses' => 'DashboardController@retentionChart'
    ]);
    Route::get('data/retention', [
        'as' => 'report', 'uses' => 'DashboardController@retentionChartData'
    ]);
    Route::get('/dailyactivity', [
        'as' => 'report', 'uses' => 'DashboardController@dailyActivity'
    ]);
    Route::get('data/dailyactivity', [
        'as' => 'report', 'uses' => 'DashboardController@dailyActivityData'
    ]);
    Route::get('/cohort', [
        'as' => 'report', 'uses' => 'DashboardController@cohort'
    ]);
    Route::get('data/cohort', [
        'as' => 'report', 'uses' => 'DashboardController@cohortData'
    ]);

    

    

});





