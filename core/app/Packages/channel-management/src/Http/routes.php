<?php

Route::group(['middleware' => ['auth'], 'prefix' => 'channel', 'namespace' => 'ChannelManage\Http\Controllers'], function()
{

    Route::get('/add', [
        'as' => 'channel.add', 'uses' => 'ChannelController@index'
    ]);
    Route::post('/', [
        'as' => 'channel.add', 'uses' => 'ChannelController@store'
    ]);
    Route::get('{id}/edit', [
        'as' => 'channel.edit', 'uses' => 'ChannelController@editView'
    ]);
    Route::post('{id}/edit', [
        'as' => 'channel.edit', 'uses' => 'ChannelController@edit'
    ]);
    Route::get('/', [
        'as' => 'channel.list', 'uses' => 'ChannelController@listView'
    ]);
    Route::get('list/json', [
        'as' => 'channel.list', 'uses' => 'ChannelController@listJson'
    ]);
    Route::post('changeState', [
        'as' => 'channel.edit', 'uses' => 'ChannelController@changeStatus'
    ]);


    

});