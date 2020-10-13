<?php

Route::group(['middleware' => [], 'prefix' => 'admin/channel', 'namespace' => 'ChannelManage\Http\Controllers'], function()
{

    Route::get('/', [
        'as' => 'index', 'uses' => 'ChannelController@index'
    ]);

    

});