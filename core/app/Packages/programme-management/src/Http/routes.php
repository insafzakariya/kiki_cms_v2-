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
    Route::get('/', [
        'as' => 'programme.list', 'uses' => 'ProgrammeController@listView'
    ]);
    Route::get('list/json', [
        'as' => 'programme.list', 'uses' => 'ProgrammeController@listJson'
    ]);
    Route::post('changeState', [
        'as' => 'programme.edit', 'uses' => 'ProgrammeController@changeStatus'
    ]);


    

});