<?php

Route::group(['middleware' => ['auth'], 'prefix' => 'scratch-card', 'namespace' => 'ScratchCardManage\Http\Controllers'], function()
{

    Route::get('/add', [
        'as' => 'scratch-card.add', 'uses' => 'ScratchCardController@index'
    ]);
    Route::post('/add', [
        'as' => 'scratch-card.add', 'uses' => 'ScratchCardController@store'
    ]);
    Route::get('{id}/edit', [
        'as' => 'scratch-card.add', 'uses' => 'ScratchCardController@editView'
    ]);
    Route::post('{id}/edit', [
        'as' => 'scratch-card.add', 'uses' => 'ScratchCardController@edit'
    ]);
    Route::get('/', [
        'as' => 'programme.list', 'uses' => 'ProgrammeSliderController@listView'
    ]);
    Route::post('/sortabledatatable', [
        'as' => 'programme.list', 'uses' => 'ProgrammeSliderController@updateOrder'
    ]);
    
    Route::get('list/json', [
        'as' => 'programme.list', 'uses' => 'ProgrammeController@listJson'
    ]);
    Route::post('changeState', [
        'as' => 'programme.edit', 'uses' => 'ProgrammeController@changeStatus'
    ]);

    Route::get('/sort', [
        'as' => 'programme.sort', 'uses' => 'ProgrammeController@sortView'
    ]);

    Route::delete('image-delete', [
        'as' => 'programme.edit', 'uses' => 'ProgrammeController@deleteImage'
    ]);


    

});