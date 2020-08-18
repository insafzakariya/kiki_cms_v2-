<?php

Route::group(['middleware' => ['auth'], 'prefix' => 'admin'], function()
{
    Route::get('music-genres/list', 'MusicGenre\Http\Controllers\MusicGenreController@getGenres')->name('admin.music-genres.index.list');
    Route::post('music-genres/image-delete', 'MusicGenre\Http\Controllers\MusicGenreController@imageDelete');
    Route::post('music-genres/{music_genres}/status-toggle', 'MusicGenre\Http\Controllers\MusicGenreController@toggleStatus')->name('admin.music-genres.status.toggle');
    Route::resource('music-genres', 'MusicGenre\Http\Controllers\MusicGenreController');
});