<?php


Route::get('/api/media/find', '\OptimistDigital\MediaField\Controllers\MediaController@findFiles');
Route::post('/api/media/upload', '\OptimistDigital\MediaField\Controllers\MediaController@uploadFile');
Route::post('/api/media/update', '\OptimistDigital\MediaField\Controllers\MediaController@updateFile');
Route::get('/api/media', '\OptimistDigital\MediaField\Controllers\MediaController@getFiles');
Route::get('/audio-thumbnail.svg', '\OptimistDigital\MediaField\Controllers\MediaController@getAudioThumbnail');
Route::get('/video-thumbnail.svg', '\OptimistDigital\MediaField\Controllers\MediaController@getVideoThumbnail');
