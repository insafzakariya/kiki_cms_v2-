<?php

Route::group(['middleware' => ['auth'], 'prefix' => 'admin'], function()
{
    Route::get('song-composers/list', 'SongComposerManage\Http\Controllers\SongComposerController@getSongComposers')->name('admin.song-composers.index.list');
    Route::post('song-composers/image-delete', 'SongComposerManage\Http\Controllers\SongComposerController@imageDelete')->name('admin.song-composers.image-delete');
    Route::get('song-composers/search', 'SongComposerManage\Http\Controllers\SongComposerController@composerSearch')->name('admin.song-composers.search');
    Route::post('song-composers/{song_composers}/status-toggle', 'SongComposerManage\Http\Controllers\SongComposerController@toggleStatus')->name('admin.song-composers.status.toggle');
    Route::resource('song-composers', 'SongComposerManage\Http\Controllers\SongComposerController');
});