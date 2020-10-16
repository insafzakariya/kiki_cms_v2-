<?php

Route::group(['middleware' => ['auth'], 'prefix' => 'channel', 'namespace' => 'ChannelManage\Http\Controllers'], function()
{

    Route::get('/add', [
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
    Route::get('/', [
        'as' => 'index', 'uses' => 'ChannelController@listView'
    ]);
    Route::get('list/json', [
        'as' => 'index', 'uses' => 'ChannelController@listJson'
    ]);


    

});