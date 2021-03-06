<?php

Route::group(['middleware' => ['auth'], 'prefix' => 'service', 'namespace' => 'KikiServiceManage\Http\Controllers'], function()
{

    Route::get('/add', [
        'as' => 'service.add', 'uses' => 'KikiServiceController@index'
    ]);
    Route::post('/add', [
        'as' => 'service.add', 'uses' => 'KikiServiceController@store'
    ]);
    Route::get('{id}/edit', [
        'as' => 'service.edit', 'uses' => 'KikiServiceController@editView'
    ]);
    Route::post('{id}/edit', [
        'as' => 'service.edit', 'uses' => 'KikiServiceController@edit'
    ]);
    Route::get('/', [
        'as' => 'service.list', 'uses' => 'KikiServiceController@listView'
    ]);
    Route::post('/sortabledatatable', [
        'as' => 'service.list', 'uses' => 'KikiServiceController@updateOrder'
    ]);
    
    Route::get('list/json', [
        'as' => 'programme-slider.list', 'uses' => 'ProgrammeController@listJson'
    ]);
    Route::post('changeState', [
        'as' => 'service..edit', 'uses' => 'KikiServiceController@changeStatus'
    ]);
    Route::post('deleteservice', [
        'as' => 'service.list', 'uses' => 'KikiServiceController@deleteSlider'
    ]);

    Route::get('/sort', [
        'as' => 'programme.sort', 'uses' => 'ProgrammeController@sortView'
    ]);

    Route::delete('image-delete', [
        'as' => 'service.edit', 'uses' => 'KikiServiceController@deleteImage'
    ]);


    

});