<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;

use SpotifyWebAPI;

//контроллер для предварительного тестирования различных функций сайта
class TestController extends Controller
{
    //страница Tests
    public function view_tests()
    {
        return view('tests');
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
        $access_txt = fopen("access_token.txt", "w") or die("Unable to open file!");
        fwrite($access_txt, $accessToken);

        $refresh_txt = fopen("refresh_token.txt", "w") or die("Unable to open file!");
        fwrite($refresh_txt, $refreshToken);

        //перебрасываем пользователя на главную страницу сайта
        header('Location: index.php');
        die();
    }
}
