<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use SpotifyWebAPI;
use Cookie;
use Auth;
use App\Globals\System;
use App\Globals\Helpers;

//контроллер с методами для предварительного тестирования различных функций сайта
class TestController extends Controller
{
    //страница Tests
    public function view_tests()
    {
        return view('tests');
    }

    //пустая функция, можно написать что угодно и проверить
    public function test_custom()
    {
      //
    }

    //тест работы PHP-wrapper'а для Spotify Web API
    public function test_spotify()
    {
        //данные для авторизации в Spotify API
        //берутся из базы данных сайта
        $client_id = config('settings')->spotify_client_id;
        $client_secret = config('settings')->spotify_client_secret;
        $redirect_uri = config('settings')->spotify_redirect_uri;

        //создание сессии
        $session = new SpotifyWebAPI\Session(
            $client_id,
            $client_secret,
            $redirect_uri
        );
        
        $api = new SpotifyWebAPI\SpotifyWebAPI();
        
        if (isset($_GET['code'])) {
            $session->requestAccessToken($_GET['code']);
            $api->setAccessToken($session->getAccessToken());
        
            //тестовый запрос, ищем 'the beatles' среди исполнителей и выводим результат
            $results = $api->search('the beatles', 'artist');
            echo "<b>Search results for \"The Beatles\"</b><br>";
            foreach ($results->artists->items as $artist) {
                echo $artist->name, '<br>';
            }

        } else {
            $options = [
                'scope' => [
                    'user-read-email',
                ],
            ];
        
            header('Location: ' . $session->getAuthorizeUrl($options));
            die();
        }
    }

    //тест работы Authorization Code Flow
    public function test_auth()
    {   
        $session = new SpotifyWebAPI\Session(
            config('settings')->spotify_client_id,
            config('settings')->spotify_client_secret,
            config('settings')->spotify_redirect_uri
        );

        $options = [
            'scope' => [
                'playlist-read-private',
                'user-read-private',
            ],
        ];

        header('Location: ' . $session->getAuthorizeUrl($options));
        die();
    }

    //callback для Authorization Code Flow
    public function test_auth_callback()
    {
        $session = new SpotifyWebAPI\Session(
            config('settings')->spotify_client_id,
            config('settings')->spotify_client_secret,
            config('settings')->spotify_redirect_uri
        );
        
        //Запрос на access token используя code из спотифая
        $session->requestAccessToken($_GET['code']);
        
        //получаем access и refresh токены
        $accessToken = $session->getAccessToken();
        $refreshToken = $session->getRefreshToken();
        
        //сохраняем токены в txt файлах (для теста)
        // $access_txt = fopen("access_token.txt", "w") or die("Unable to open file!");
        // fwrite($access_txt, $accessToken);

        // $refresh_txt = fopen("refresh_token.txt", "w") or die("Unable to open file!");
        // fwrite($refresh_txt, $refreshToken);

        //сохраняем токены в cookies (для теста)
        Cookie::queue('test_spotify_access_token', $accessToken, 60*24*30);
        Cookie::queue('test_spotify_refresh_token', $refreshToken, 60*24*30);

        //редирект на главную
        return redirect('/');
    }

    //показать cookies
    public function test_cookies(Request $request)
    {
        echo "access_token: " . $request->cookie('spotify_access_token') . "<br><br>";
        echo "refresh token: " . $request->cookie('spotify_refresh_token') . "<br><br>";
        echo "access expiration: " . $request->cookie('spotify_access_expiration');
    }

    //тест laravel api + vue
    public function test_api()
    {
        $settings = config('settings');
        return response()->json($settings);
    }

    //тест библиотеки
    public function test_library(Request $request)
    {
        $api = new SpotifyWebAPI\SpotifyWebAPI();
        $spotify_access_token = $request->cookie('spotify_access_token');
        $api->setAccessToken($spotify_access_token);
        $tracks = $api->getMySavedTracks([
            'limit' => 10,
        ]);
        
        foreach ($tracks->items as $track) 
        {
            $track = $track->track;
        
            echo '<a href="' . $track->external_urls->spotify . '">' . $track->name . '</a> <br>';
        }
    }
}
