<?php

Route::group(['middleware' => [], 'prefix' => 'admin/channel', 'namespace' => 'ChannelManage\Http\Controllers'], function()
{

    Route::get('/', [
        'as' => 'index', 'uses' => 'ChannelController@index'
    ]);
    Route::post('/', [
        'as' => 'index', 'uses' => 'ChannelController@store'
    ]);
    Route::get('{id}/edit', [
        'as' => 'index', 'uses' => 'ChannelController@editView'
    ]);
    Route::post('{id}/edit', [
        'as' => 'index', 'uses' => 'ChannelController@edit'
    ]);


    

});