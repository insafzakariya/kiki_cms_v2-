<?php

Route::group(['middleware' => ['auth'], 'prefix' => 'admin'], function()
{
    Route::get('radio-channels/list', 'RadioChannel\Http\Controllers\RadioChannelController@getChannels')->name('admin.radio-channels.index.list');
    Route::post('radio-channels/image-delete', 'RadioChannel\Http\Controllers\RadioChannelController@imageDelete');
    Route::post('radio-channels/{radio_channels}/status-toggle', 'RadioChannel\Http\Controllers\RadioChannelController@toggleStatus')->name('admin.radio-channels.status.toggle');
    Route::resource('radio-channels', 'RadioChannel\Http\Controllers\RadioChannelController');
});