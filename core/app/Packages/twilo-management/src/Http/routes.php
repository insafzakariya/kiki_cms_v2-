<?php

Route::group(['middleware' => ['auth'], 'prefix' => 'twillio', 'namespace' => 'TwiloManage\Http\Controllers'], function()
{

    Route::get('/add', [
        'as' => 'twilo.add', 'uses' => 'TwiloChatController@createChannelView'
    ]);
    Route::post('/add', [
        'as' => 'twilo.add', 'uses' => 'TwiloChatController@channelStore'
    ]);
    Route::get('member/add', [
        'as' => 'twilo.add', 'uses' => 'TwiloChatController@createChannelMemberView'
    ]);
    Route::post('member/add', [
        'as' => 'twilo.add', 'uses' => 'TwiloChatController@ChannelMemberStore'
    ]);
    Route::get('search/viewer', [
        'as' => 'twilo.add', 'uses' => 'TwiloChatController@searchViwer'
    ]);
    
    Route::get('/', [
        'as' => 'twilo.list', 'uses' => 'TwiloChatController@listView'
    ]);
    Route::get('list/json', [
        'as' => 'twilo.list', 'uses' => 'TwiloChatController@listJson'
    ]);
    Route::post('changeState', [
        'as' => 'channel.edit', 'uses' => 'ChannelController@changeStatus'
    ]);
    Route::post('delete', [
        'as' => 'channel.list', 'uses' => 'ChannelController@deleteChannel'
    ]);


    

});