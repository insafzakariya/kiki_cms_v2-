<?php

Route::group(['middleware' => ['auth'], 'prefix' => 'admin'], function()
{
    Route::get('products/list', 'ProductManage\Http\Controllers\ProductController@getProducts')->name('admin.products.index.list');
    Route::post('products/{products}/status-toggle', 'ProductManage\Http\Controllers\ProductController@toggleStatus')->name('admin.products.status.toggle');
    Route::resource('products', 'ProductManage\Http\Controllers\ProductController');
    Route::get('products/{id}/add/step-2','ProductManage\Http\Controllers\ProductController@step2View')->name('admin.products.add');
    Route::get('products/{id}/add/songs','ProductManage\Http\Controllers\ProductController@songAddView')->name('admin.products.song-add');
    Route::post('products/add/step-2','ProductManage\Http\Controllers\ProductController@step2Save')->name('admin.products.add');
    Route::post('products/add/step-3/redirect','ProductManage\Http\Controllers\ProductController@redirectToStep3')->name('admin.products.add');
    Route::get('products/{id}/add/step-3','ProductManage\Http\Controllers\ProductController@step3View')->name('admin.products.add');
    Route::get('products/{id}/get/songs','ProductManage\Http\Controllers\ProductController@getSongsOfProduct')->name('admin.products.song-product');
    Route::post('products/add/step-3','ProductManage\Http\Controllers\ProductController@step3Save')->name('admin.products.add');
    Route::post('products/songs/order', 'ProductManage\Http\Controllers\ProductController@orderSongs')->name('admin.products.song-order');
    Route::post('product/song/remove', 'ProductManage\Http\Controllers\ProductController@removeSongFromProduct')->name('admin.products.remove-song');

    Route::get('products/view/{id}','ProductManage\Http\Controllers\ProductController@showEdit')->name('admin.products.add');
    
});