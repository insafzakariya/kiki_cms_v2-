<?php

Route::group(['middleware' => ['auth']], function() {
    Route::group(['prefix' => 'admin', 'namespace' => 'SongsCategory\Http\Controllers'], function () {

        Route::get('songs-category', 'SongsCategoryController@index')->name('songs-category.index');
        Route::post('songs-category/image-delete', 'SongsCategoryController@imageDelete');
        Route::get('songs-category/create', 'SongsCategoryController@create')->name('songs-category.create');
        Route::post('songs-category/store', 'SongsCategoryController@store')->name('songs-category.store');
        Route::get('songs-category/edit/{id}', 'SongsCategoryController@edit')->name('songs-category.edit');
        Route::post('songs-category/update/{songs_category}', 'SongsCategoryController@update')->name('songs-category.update');
        Route::post('songs-category/{songs_category}/status-toggle', 'SongsCategoryController@toggleStatus')->name('admin.songs-category.status.toggle');
    });
});
