<?php

Route::group(['middleware' => ['auth'], 'prefix' => 'programme', 'namespace' => 'ProgrammeManage\Http\Controllers'], function()
{

    Route::get('/add', [
        'as' => 'programme.add', 'uses' => 'ProgrammeController@index'
    ]);
    Route::post('/add', [
        'as' => 'programme.add', 'uses' => 'ProgrammeController@store'
    ]);
    Route::get('{id}/edit', [
        'as' => 'programme.edit', 'uses' => 'ProgrammeController@editView'
    ]);
    Route::post('{id}/edit', [
        'as' => 'programme.edit', 'uses' => 'ProgrammeController@edit'
    ]);
    Route::get('{id}/policy', [
        'as' => 'programme.edit', 'uses' => 'ProgrammeController@policyView'
    ]);
    Route::post('{id}/policy', [
        'as' => 'programme.edit', 'uses' => 'ProgrammeController@policy'
    ]);
    Route::get('/', [
        'as' => 'programme.list', 'uses' => 'ProgrammeController@listView'
    ]);
    Route::get('list/json', [
        'as' => 'programme.list', 'uses' => 'ProgrammeController@listJson'
    ]);
    Route::post('changeState', [
        'as' => 'programme.edit', 'uses' => 'ProgrammeController@changeStatus'
    ]);
    Route::post('delete', [
        'as' => 'programme.list', 'uses' => 'ProgrammeController@programmeDelete'
    ]);

    Route::get('/sort', [
        'as' => 'programme.sort', 'uses' => 'ProgrammeController@sortView'
    ]);

    //Get Unsorted & sorted List

    Route::get('unsortedList', [
        'as' => 'programme.sort', 'uses' => 'ProgrammeController@getUnsortedList'
    ]);
    Route::get('sortedList', [
        'as' => 'programme.sort', 'uses' => 'ProgrammeController@getsortedList'
    ]);

    //Update Sorted & Unsorted List

    Route::post('updateSortedProgrammes', [
        'as' => 'programme.sort', 'uses' => 'ProgrammeController@updateSortedProgrammes'
    ]);

    Route::delete('image-delete', [
        'as' => 'programme.edit', 'uses' => 'ProgrammeController@deleteImage'
    ]);


    

});