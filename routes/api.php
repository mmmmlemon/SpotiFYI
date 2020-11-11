<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//тесты
Route::get('/test_api', 'TestController@test_api'); //проверка работы api laravel


Route::get('/get_spotify_tracks_count', 'SpotifyAPIController@getSpotifyUserTracksCount'); //домашняя страница
Route::get('/get_spotify_username', 'SpotifyAPIController@getSpotifyUsername');
