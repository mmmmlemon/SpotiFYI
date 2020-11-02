<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;

use SpotifyWebAPI;

//контроллер для предварительного тестирования различных функций сайта
class TestController extends Controller
{
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
}
