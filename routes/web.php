<?php
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(
    ['register' => false]
);

Route::group(['middleware' => 'auth'], function() {
    Route::resource('files', 'FilesController')->only(['store', 'index']);
});
