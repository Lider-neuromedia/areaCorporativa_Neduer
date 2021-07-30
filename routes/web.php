<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::get('/', 'HomeController@index');

Route::get('/login', 'HomeController@loginForm')->name('custom.loginForm');
Route::post('/login', 'HomeController@login')->name('custom.login');
Route::post('/logout', 'HomeController@logout')->name('custom.logout');

Route::get('/files-refresh', 'FilesController@index')->name('files.refresh');
Route::post('/files-refresh', 'FilesController@preloadFileList')->name('files.preload');
Route::get('/files-list', 'HomeController@files')->name('files.list');
Route::get('/files/{path?}', 'HomeController@file')->where('path', '(.*)')->name('files.download');

// Auth::routes(['register' => false]);
