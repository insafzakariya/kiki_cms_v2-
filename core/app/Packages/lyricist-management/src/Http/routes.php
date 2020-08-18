<?php

Route::group(['middleware' => ['auth'], 'prefix' => 'admin'], function()
{
    Route::get('lyricists/list', 'LyricistManage\Http\Controllers\LyricistController@getLyricists')->name('admin.lyricists.index.list');
    Route::get('lyricists/search', 'LyricistManage\Http\Controllers\LyricistController@lyricsSearch')->name('admin.lyricists.search');
    Route::post('lyricists/{lyricists}/status-toggle', 'LyricistManage\Http\Controllers\LyricistController@toggleStatus')->name('admin.lyricists.status.toggle');
    Route::resource('lyricists', 'LyricistManage\Http\Controllers\LyricistController');
});