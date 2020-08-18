<?php

Route::group(['middleware' => ['auth'], 'prefix' => 'admin'], function()
{
    Route::get('moods/list', 'MoodManage\Http\Controllers\MoodController@getMoods')->name('admin.moods.index.list');
    Route::post('moods/{moods}/status-toggle', 'MoodManage\Http\Controllers\MoodController@toggleStatus')->name('admin.moods.status.toggle');
    Route::resource('moods', 'MoodManage\Http\Controllers\MoodController');
});