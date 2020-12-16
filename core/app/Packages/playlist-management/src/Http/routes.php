<?php

Route::group(['middleware' => ['auth'], 'prefix' => 'admin', 'namespace' => 'PlaylistManage\Http\Controllers'], function()
{

    Route::get('playlist/step-1', [
        'as' => 'admin.playlist.add', 'uses' => 'PlaylistController@step1View'
    ]);

    Route::post('playlist/step-1', [
        'as' => 'admin.playlist.add', 'uses' => 'PlaylistController@step1Save'
    ]);


    Route::get('playlist/step-2', [
        'as' => 'admin.playlist.add', 'uses' => 'PlaylistController@step2View'
    ]);

    Route::get('playlist/song/list', [
        'as' => 'admin.playlist.add', 'uses' => 'PlaylistController@songAddDataLoad'
    ]);

    Route::post('playlist/step-2', [
        'as' => 'admin.playlist.add', 'uses' => 'PlaylistController@step2Save'
    ]);

    Route::get('playlist/step-3', [
        'as' => 'admin.playlist.add', 'uses' => 'PlaylistController@step3View'
    ]);

    Route::get('playlist/songs', [
        'as' => 'admin.playlist.add', 'uses' => 'PlaylistController@getSongsOfPlaylist'
    ]);
    Route::post('playlist/songs/order', [
        'as' => 'admin.playlist.songs-order', 'uses' => 'PlaylistController@orderSongs'
    ]);
    Route::post('playlist/song/remove', [
        'as' => 'admin.playlist.add', 'uses' => 'PlaylistController@removeSongFromPlaylist'
    ]);

    Route::post('playlist/step-3', [
        'as' => 'admin.playlist.add', 'uses' => 'PlaylistController@step3Save'
    ]);

    Route::get('playlist', [
        'as' => 'admin.playlist.index', 'uses' => 'PlaylistController@listView'
    ]);

    Route::get('playlist/json/list', [
        'as' => 'admin.playlist.index.list', 'uses' => 'PlaylistController@jsonList'
    ]);

    Route::post('playlist/changeState', [
        'as' => 'admin.playlist.status.toggle', 'uses' => 'PlaylistController@changeStatus'
    ]);

    Route::get('playlist/edit/{id}', [
        'as' => 'admin.playlist.add', 'uses' => 'PlaylistController@editPlaylist'
    ]);


    Route::get('playlist/searchalbum', [
        'as' => 'admin.playlist.searchalbum', 'uses' => 'PlaylistController@searchAlbum'
    ]);
    Route::get('playlist/searchPlaylist', [
        'as' => 'admin.playlist.searchalbum', 'uses' => 'PlaylistController@searchPlaylist'
    ]);

});