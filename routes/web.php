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

Route::get('/', function () {
    return view('home_page');
});

//тесты
Route::get('/tests', 'TestController@view_tests'); //ссылки на все тесты
Route::get('/test_spotify', 'TestController@test_spotify'); //тест работы Spotify WEB Api
Route::get('/test_auth', 'TestController@test_auth'); //тест Авторизации
Route::get('/test_callback', 'TestController@test_auth_callback'); //callback для авторизации
Route::get('/test_cookies', 'TestController@test_cookies'); //тест Cookies

