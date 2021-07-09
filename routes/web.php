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
Route::get('/files/{path?}', 'HomeController@file')->where('path', '(.*)');

// Auth::routes(['register' => false]);
