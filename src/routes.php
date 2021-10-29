<?php


Route::get('/api/media/find', '\OptimistDigital\MediaField\Controllers\MediaController@findFiles');
Route::post('/api/media/upload', '\OptimistDigital\MediaField\Controllers\MediaController@uploadFile');
Route::post('/api/media/update', '\OptimistDigital\MediaField\Controllers\MediaController@updateFile');
Route::get('/api/media', '\OptimistDigital\MediaField\Controllers\MediaController@getFiles');
Route::delete('/api/media/delete', '\OptimistDigital\MediaField\Controllers\MediaController@deleteFiles');
