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
        'as' => 'scratch-card.list', 'uses' => 'ScratchCardController@listView'
    ]);
   
    
    Route::get('list/json', [
        'as' => 'scratch-card.list', 'uses' => 'ScratchCardController@listJson'
    ]);
    Route::get('{id}/code', [
        'as' => 'scratch-card.list', 'uses' => 'ScratchCardController@codeView'
    ]);
    Route::get('code/list/json/{id}', [
        'as' => 'scratch-card.list', 'uses' => 'ScratchCardController@codeListJson'
    ]);
    // Route::post('changeState', [
    //     'as' => 'programme.edit', 'uses' => 'ProgrammeController@changeStatus'
    // ]);
    Route::post('delete', [
        'as' => 'scratch-card.list', 'uses' => 'ScratchCardController@delete'
    ]);



    

});