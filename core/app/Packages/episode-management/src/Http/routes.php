<?php

Route::group(['middleware' => ['auth'], 'prefix' => 'episode', 'namespace' => 'EpisodeManage\Http\Controllers'], function()
{

    Route::get('/add', [
        'as' => 'episode.add', 'uses' => 'EpisodeController@index'
    ]);
    Route::post('/add', [
        'as' => 'episode.add', 'uses' => 'EpisodeController@store'
    ]);
    Route::get('{id}/edit', [
        'as' => 'episode.edit', 'uses' => 'EpisodeController@editView'
    ]);
    Route::post('{id}/edit', [
        'as' => 'episode.edit', 'uses' => 'EpisodeController@edit'
    ]);
    Route::get('/', [
        'as' => 'episode.list', 'uses' => 'EpisodeController@listView'
    ]);
    Route::get('{id}/programme/episode', [
        'as' => 'episode.list', 'uses' => 'EpisodeController@listViewByProgramme'
    ]);
    Route::get('list/json', [
        'as' => 'episode.list', 'uses' => 'EpisodeController@listJson'
    ]);
    Route::get('list/programme/json', [
        'as' => 'episode.list', 'uses' => 'EpisodeController@listJsonByProgramme'
    ]);
    Route::post('delete', [
        'as' => 'episode.edit', 'uses' => 'EpisodeController@delete'
    ]);
    Route::get('policyupdate/{ids}', [
        'as' => 'episode.list', 'uses' => 'EpisodeController@policyBulkUpdate'
    ]);
    Route::post('policyupdate/{ids}', [
        'as' => 'episode.list', 'uses' => 'EpisodeController@updatePolicyBulkUpdate'
    ]);

    //Get Programme Search 
    Route::get('search/programme', [
        'as' => 'episode.add', 'uses' => 'EpisodeController@programmeSearch'
    ]);


    Route::get('/sort', [
        'as' => 'episode.sort', 'uses' => 'EpisodeController@sortView'
    ]);

    //Get Unsorted & sorted List

    Route::get('unsortedList', [
        'as' => 'episode.sort', 'uses' => 'EpisodeController@getUnsortedList'
    ]);
    Route::get('sortedList', [
        'as' => 'episode.sort', 'uses' => 'EpisodeController@getsortedList'
    ]);

    //Update Sorted & Unsorted List

    Route::post('updateSortedProgrammes', [
        'as' => 'episode.sort', 'uses' => 'EpisodeController@updateSortedProgrammes'
    ]);


    

});