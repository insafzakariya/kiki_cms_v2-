<?php
/**
 * MENU MANAGEMENT ROUTES
 *
 * @version 1.0.0
 * @author Yasith Samarawickrama <yazith11@gmail.com>
 * @copyright 2015 Yasith Samarawickrama
 */

/**
 * USER AUTHENTICATION MIDDLEWARE
 */
Route::group(['middleware' => ['auth']], function()
{
    Route::group(['prefix' => 'menu', 'namespace' => 'MenuManage\Http\Controllers'], function(){
      /**
       * GET Routes
       */
      Route::get('add', [
        'as' => 'menu.add', 'uses' => 'MenuController@addView'
      ]);

      Route::get('edit/{id}', [
        'as' => 'menu.edit', 'uses' => 'MenuController@editView'
      ]);

      Route::get('list', [
        'as' => 'menu.list', 'uses' => 'MenuController@listView'
      ]);

      Route::get('json/list', [
        'as' => 'menu.list', 'uses' => 'MenuController@jsonList'
      ]);

      /**
       * POST Routes
       */
      Route::post('add', [
        'as' => 'menu.add', 'uses' => 'MenuController@add'
      ]);

      Route::post('edit/{id}', [
        'as' => 'menu.edit', 'uses' => 'MenuController@edit'
      ]);

      Route::post('status', [
        'as' => 'menu.status', 'uses' => 'MenuController@status'
      ]);

      Route::post('delete', [
        'as' => 'menu.delete', 'uses' => 'MenuController@delete'
      ]);
    });
});