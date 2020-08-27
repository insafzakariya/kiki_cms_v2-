<?php

Route::group(['middleware' => ['auth'], 'prefix' => 'admin', 'namespace' => 'SongManage\Http\Controllers'], function()
{

    Route::get('song/step-1/{id?}', [
        'as' => 'song.add', 'uses' => 'SongController@step1View'
    ]);

    Route::get('song/bulk-upload', [
        'as' => 'song.bulk-upload', 'uses' => 'BulkUploadController@songUpload'
    ]);
    Route::post('song/bulk-upload', [
        'as' => 'song.bulk-upload', 'uses' => 'BulkUploadController@upload'
    ]);

    Route::get('song/category/{id}/subcategory', [
        'as' => 'song.add', 'uses' => 'SongController@getSubCategoriesByParentId'
    ]);

    Route::post('song/step-1/{id?}', [
        'as' => 'song.add', 'uses' => 'SongController@step1Save'
    ]);

    Route::get('song/step-2/{id?}', [
        'as' => 'song.add', 'uses' => 'SongController@step2View'
    ]);


    Route::post('song/step-2/{id}', [
        'as' => 'song.add', 'uses' => 'SongController@step2Save'
    ]);

    Route::get('song/step-3/{id?}', [
        'as' => 'song.add', 'uses' => 'SongController@step3View'
    ]);

    Route::post('song/step-3/{id?}', [
        'as' => 'song.add', 'uses' => 'SongController@step3Save'
    ]);

    Route::post('song/image-delete', [
        'as' => 'song.add', 'uses' => 'SongController@imageDelete'
    ]);
    Route::post('song/audio-delete', [
        'as' => 'song.add', 'uses' => 'SongController@audioDelete'
    ]);

    Route::get('song/search-publisher', [
        'as' => 'song.add', 'uses' => 'SongController@searchPublisher'
    ]);

    Route::get('song', [
        'as' => 'song.index', 'uses' => 'SongController@listView'
    ]);

    Route::get('song/json/list', [
        'as' => 'song.index.list', 'uses' => 'SongController@jsonList'
    ]);

    Route::post('song/changeState', [
        'as' => 'song.status', 'uses' => 'SongController@changeStatus'
    ]);

    Route::get('song/edit/{id}', [
        'as' => 'song.index.list', 'uses' => 'SongController@editSong'
    ]);

    Route::get('song/songsearch', [
        'as' => 'song.searchcombo', 'uses' => 'SongController@songSearch'
    ]);


});