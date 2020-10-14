<?php

Route::group([
    'middleware' => ['auth'],
    'prefix' => 'admin',
    'namespace' => 'ArtistManage\Http\Controllers'
], function () {
    Route::get('artists/list', 'ArtistController@getArtists')->name('admin.artists.index.list');
    Route::post('artists/image-delete', 'ArtistController@imageDelete')->name('artists.image-delete');
    Route::get('artists/search', 'ArtistController@artistSearch')->name('artists.search');
    Route::post('artists/{artists}/status-toggle', 'ArtistController@toggleStatus')->name('admin.artists.status.toggle');
    Route::resource('artists', 'ArtistController');
});