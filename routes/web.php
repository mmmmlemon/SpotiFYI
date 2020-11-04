<?php

use Illuminate\Support\Facades\Route;

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

//тесты
Route::get('/test_spotify', 'TestController@test_spotify'); //тест работы Spotify WEB Api
Route::get('/test_auth', 'TestController@test_auth'); //тест Авторизации
Route::get('/test_callback', 'TestController@test_auth_callback'); //callback для авторизации
Route::get('/test_cookies', 'TestController@test_cookies'); //тест Cookies

Auth::routes();

//вывод vue router 
Route::get('/{any}', 'HomeController@vue_router')->where('any', '.*');
