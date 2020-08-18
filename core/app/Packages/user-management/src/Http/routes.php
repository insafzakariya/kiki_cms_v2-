<?php
/**
 * USER MANAGEMENT ROUTES
 *
 * @version 1.0.0
 * @author Insaf Zakariya <insaf.zak@gmail.com>
 * @copyright 2015 Yasith Samarawickrama
 */

/**
 * USER AUTHENTICATION MIDDLEWARE
 */
Route::group(['middleware' => ['auth']], function()
{
  Route::group(['prefix' => 'user', 'namespace' => 'UserManage\Http\Controllers'], function(){
      /**
       * GET Routes
       */

       // start account setting route
       // Route::get('account-settings', 'UserController@accountSettingsView');
      Route::get('account-settings', [
        'as' => 'account.setting', 'uses' => 'UserController@accountSettingsView'
      ]);
      Route::post('account-settings',  [
       'as' => 'account.setting', 'uses' => 'UserController@setAccountSettings'
     ]);

       // end account setting route
      Route::get('add', [
        'as' => 'user.add', 'uses' => 'UserController@addView'
      ]);

      Route::get('edit/{id}', [
        'as' => 'user.edit', 'uses' => 'UserController@editView'
      ]);

      Route::get('list/{type}', [
        'as' => 'user.list', 'uses' => 'UserController@listView'
      ]);

      Route::get('json/list/{type}', [
        'as' => 'user.list', 'uses' => 'UserController@jsonList'
      ]);

      Route::get('admin/profile', [
        'as' => 'user.admin.profile', 'uses' => 'UserController@profileView'
      ]);

      Route::get('view/{id}', [
        'as' => 'user.view', 'uses' => 'UserController@viewUser'
      ]);

      Route::get('view/{id}/products-data', [
        'as' => 'user.view', 'uses' => 'UserController@getserProductsData'
      ]);


      /**
       * POST Routes
       */
      Route::post('add', [
        'as' => 'user.add', 'uses' => 'UserController@add'
      ]);

      Route::post('edit/{id}', [
        'as' => 'user.edit', 'uses' => 'UserController@edit'
      ]);
      Route::post('list/{type}', [
        'as' => 'user.list', 'uses' => 'UserController@changerole'
      ]);

      Route::post('status', [
        'as' => 'user.status', 'uses' => 'UserController@status'
      ]);

      Route::post('delete', [
        'as' => 'user.delete', 'uses' => 'UserController@delete'
      ]);

      Route::post('admin/profile', [
        'as' => 'user.admin.profile', 'uses' => 'UserController@updateProfile'
      ]);

      Route::post('password', [
        'as' => 'user.admin.password', 'uses' => 'UserController@password'
      ]); 
      Route::post('genarate_password', [
        'as' => 'user.edit', 'uses' => 'UserController@genarate_password'
      ]);

      Route::post('approve', [
        'as' => 'user.approve', 'uses' => 'UserController@merchantUserApprove'
      ]);

      Route::post('reject', [
        'as' => 'user.reject', 'uses' => 'UserController@merchantUserReject'
      ]);
      Route::post('status-toggle', [
        'as' => 'user.reject', 'uses' => 'UserController@toggleUser'
      ]);
    });
});
