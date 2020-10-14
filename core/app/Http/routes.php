<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
 */

/**
 * USER AUTHENTICATION MIDDLEWARE
 */

/*DONT USE THIS ROUTE FOR OTHER USAGE ----ONLY FOR THIS */
Route::group(['middleware' => ['auth']], function () {
    Route::get('admin', [
        'as' => 'dashboard', 'uses' => 'DashboardController@index',
    ]);
    Route::get('/', [
        'as' => 'index', 'uses' => 'DashboardController@index',
    ]);


});

Route::group(['middleware' => ['auth_front']], function () {

});

/* Solr Routes */
Route::group(['prefix' => 'solr'], function () {
    Route::get('kiki_music_ping', [
        'as' => 'index', 'uses' => 'SolrController@kiki_music_ping',
    ]);
    Route::get('solr_test', [
        'as' => 'index', 'uses' => 'WebController@solr_test',
    ]);
});



Route::group(['middleware' => ['auth'], 'prefix' => 'admin/solr'], function () {
    Route::get('song', [
        'as' => 'index', 'uses' => 'SolrUploadController@allSongs',
    ]);
    Route::get('song/{id?}', [
        'as' => 'index', 'uses' => 'SolrUploadController@song',
    ]);
    Route::get('song/delete', [
        'as' => 'index', 'uses' => 'SolrUploadController@songDeleteAll',
    ]);
    Route::get('song/delete/{id?}', [
        'as' => 'index', 'uses' => 'SolrUploadController@songDelete',
    ]);
    Route::get('artist', [
        'as' => 'index', 'uses' => 'SolrUploadController@allArtist',
    ]);
    Route::get('artist/{id}', [
        'as' => 'index', 'uses' => 'SolrUploadController@artist',
    ]);
    Route::get('artist/delete', [
        'as' => 'index', 'uses' => 'SolrUploadController@artistDeleteAll',
    ]);
    Route::get('artist/delete/{id}', [
        'as' => 'index', 'uses' => 'SolrUploadController@artistDelete',
    ]);
    Route::get('playlist', [
        'as' => 'index', 'uses' => 'SolrUploadController@allPlaylist',
    ]);
    Route::get('playlist/{id}', [
        'as' => 'index', 'uses' => 'SolrUploadController@playlist',
    ]);
    Route::get('playlist/delete/{id}', [
        'as' => 'index', 'uses' => 'SolrUploadController@playlistDelete',
    ]);
    Route::get('playlist/delete', [
        'as' => 'index', 'uses' => 'SolrUploadController@playlistDeleteAll',
    ]);
    Route::get('album', [
        'as' => 'index', 'uses' => 'SolrUploadController@allAlbum',
    ]);
    Route::get('album/{id}', [
        'as' => 'index', 'uses' => 'SolrUploadController@album',
    ]);
    Route::get('album/delete', [
        'as' => 'index', 'uses' => 'SolrUploadController@albumDeleteAll',
    ]);
    Route::get('album/delete/{id}', [
        'as' => 'index', 'uses' => 'SolrUploadController@albumDelete',
    ]);
    Route::get('clear', [
        'as' => 'index', 'uses' => 'SolrUploadController@removeAllSolr',
    ]);
});

/**
 * USER REGISTRATION & LOGIN
 */

Route::get('user/login', [
    'as' => 'user.login', 'uses' => 'AuthController@loginView',
]);
Route::post('user/login', [
    'as' => 'user.login', 'uses' => 'AuthController@login',
]);

Route::get('user/logout', [
    'as' => 'user.logout', 'uses' => 'AuthController@logout',
]);

/*FRONT LOGIN */

Route::get('front/login', [
    'as' => 'front.login', 'uses' => 'AuthController@loginView_front',
]);
Route::post('front/login', [
    'as' => 'front.login', 'uses' => 'AuthController@login_front',
]);
Route::get('front/logout', [
    'as' => 'front.logout', 'uses' => 'AuthController@logout_front',
]);

/**
 * USER LOGIN VIA FACEBOOK/GOOGLE/TWITTER/LINKDIN ETC
 */

Route::get('auth/facebook', 'AuthController@redirectToFacebook');
Route::get('auth/facebook/callback', 'AuthController@handleFacebookCallback');

Route::get('auth/google', 'AuthController@redirectToGoogle');
Route::get('auth/google/callback', 'AuthController@handleGoogleCallback');

Route::get('google/cloud/store', 'ImageController@upload_google_bucket_form');
Route::post('google/cloud/store', 'ImageController@upload_google_bucket_post_form');

Route::get('google/cloud/store/get', 'ImageController@get_image_bucket');
