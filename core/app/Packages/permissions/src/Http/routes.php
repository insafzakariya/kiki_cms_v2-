<?php
/**
 * PERMISSIONS MANAGEMENT ROUTES
 *
 * @version 1.0.0
 * @author Insaf Zakariya
 * @copyright 2016 Insaf Zakariya
 */

/**
 * USER AUTHENTICATION MIDDLEWARE
 */
Route::group(['middleware' => ['auth']], function()
{
    Route::group(['prefix' => 'permission', 'namespace' => 'Permissions\Http\Controllers'], function(){
      /**
       * GET Routes
       */
      Route::get('add', [
        'as' => 'permission.add', 'uses' => 'PermissionController@addView'  
      ]);
      Route::get('edit/{id}', [
        'as' => 'permission.edit', 'uses' => 'PermissionController@editView'  
      ]);

      Route::get('list', [
        'as' => 'permission.list', 'uses' => 'PermissionController@listView'
      ]);

      Route::get('json/list', [
        'as' => 'permission.list', 'uses' => 'PermissionController@jsonList'
      ]);
 

      /**
       * POST Routes
       */
       Route::post('add', [
        'as' => 'permission.add', 'uses' => 'PermissionController@add'  
      ]);
      Route::post('edit/{id}', [
        'as' => 'permission.edit', 'uses' => 'PermissionController@edit'  // Not Implemented Yet
      ]);

      Route::post('status', [
        'as' => 'permission.status', 'uses' => 'PermissionController@status'
      ]);

      Route::post('delete', [
        'as' => 'permission.delete', 'uses' => 'PermissionController@delete'
      ]);
    });
});