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
Route::get('/tests', 'TestController@view_tests');

Route::get('/test_spotify', 'TestController@test_spotify');

Route::get('/test_auth', 'TestController@test_auth');

Route::get('/test_callback', 'TestController@test_auth_callback');

