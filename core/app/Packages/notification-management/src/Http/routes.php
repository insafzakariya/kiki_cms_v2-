<?php

Route::group(['middleware' => ['auth'], 'prefix' => 'admin', 'namespace' => 'NotificationManage\Http\Controllers'], function()
{
    Route::get('user-group', [
        'as' => 'user-group.index', 'uses' => 'BulkUploadController@listView'
    ]);
    Route::get('user-group/user-group-upload', [
        'as' => 'user-group.bulk-upload', 'uses' => 'BulkUploadController@userGroupUpload'
    ]);
    Route::post('user-group/user-group-upload', [
        'as' => 'user-group.bulk-upload', 'uses' => 'BulkUploadController@upload'
    ]);
    Route::get('user-group/json/list', [
        'as' => 'user-group.index', 'uses' => 'BulkUploadController@jsonList'
    ]);
    Route::post('user-group/changeState', [
        'as' => 'user-group.status', 'uses' => 'BulkUploadController@changeStatus'
    ]);

    Route::get('notification', [
        'as' => 'notification.index', 'uses' => 'NotificationController@listView'
    ]);
    Route::get('notification/json/list', [
        'as' => 'notification.index', 'uses' => 'NotificationController@jsonList'
    ]);
    Route::get('notification/notification-add', [
        'as' => 'notification.index', 'uses' => 'NotificationController@addView'
    ]);
    Route::post('notification/changeState', [
        'as' => 'notification.index', 'uses' => 'NotificationController@changeStatus'
    ]);    
    Route::get('notification/searchprogram', [
        'as' => 'notification.index', 'uses' => 'NotificationController@searchprogram'
    ]);
    Route::get('notification/searchepisode', [
        'as' => 'notification.index', 'uses' => 'NotificationController@searchepisode'
    ]);
    Route::get('notification/searchuser', [
        'as' => 'notification.index', 'uses' => 'NotificationController@searchuser'
    ]);

    Route::post('notification/notification-add', 'NotificationController@addNotification');
    

    


    /*Route::get('song/step-1/{id?}', [
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
    ]);*/


});