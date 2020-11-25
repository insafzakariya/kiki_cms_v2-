<?php

Route::group(['middleware' => ['auth'], 'prefix' => 'programme-slider', 'namespace' => 'ProgrammeSliderManage\Http\Controllers'], function()
{

    Route::get('/add', [
        'as' => 'programme-slider.add', 'uses' => 'ProgrammeSliderController@index'
    ]);
    Route::post('/add', [
        'as' => 'programme-slider.add', 'uses' => 'ProgrammeSliderController@store'
    ]);
    Route::get('{id}/edit', [
        'as' => 'programme-slider.edit', 'uses' => 'ProgrammeSliderController@editView'
    ]);
    Route::post('{id}/edit', [
        'as' => 'programme-slider.edit', 'uses' => 'ProgrammeSliderController@edit'
    ]);
    Route::get('/', [
        'as' => 'programme-slider.list', 'uses' => 'ProgrammeSliderController@listView'
    ]);
    Route::post('/sortabledatatable', [
        'as' => 'programme-slider.list', 'uses' => 'ProgrammeSliderController@updateOrder'
    ]);
    
    Route::get('list/json', [
        'as' => 'programme-slider.list', 'uses' => 'ProgrammeController@listJson'
    ]);
    Route::post('changeState', [
        'as' => 'programme-slider.edit', 'uses' => 'ProgrammeSliderController@changeStatus'
    ]);
    Route::post('deleteSlider', [
        'as' => 'programme-slider.list', 'uses' => 'ProgrammeSliderController@deleteSlider'
    ]);

    Route::get('/sort', [
        'as' => 'programme.sort', 'uses' => 'ProgrammeController@sortView'
    ]);

    Route::delete('image-delete', [
        'as' => 'programme.edit', 'uses' => 'ProgrammeController@deleteImage'
    ]);


    

});