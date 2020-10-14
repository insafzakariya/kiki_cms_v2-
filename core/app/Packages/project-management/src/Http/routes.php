<?php

Route::group(['middleware' => ['auth'], 'prefix' => 'admin'], function()
{
    Route::get('projects/list', 'ProjectManage\Http\Controllers\ProjectController@getProjects')->name('admin.projects.index.list');
    Route::post('projects/{projects}/status-toggle', 'ProjectManage\Http\Controllers\ProjectController@toggleStatus')->name('admin.projects.status.toggle');
    Route::resource('projects', 'ProjectManage\Http\Controllers\ProjectController');
    Route::post('projects/image-delete', 'ProjectManage\Http\Controllers\ProjectController@imageDelete')->name('admin.projects.image-delete');
});