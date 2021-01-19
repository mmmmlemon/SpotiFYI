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
    public function test_custom(Request $request)
    {   
        $checkToken = System::checkSpotifyAccessToken($request);

        $api = config('spotify_api');

        $track = $api->getTrack('4COR2ZPEyUn0lsbAouRWxA');

        return Helpers::getItemReleaseDate($track);

        // return Helpers::getFull();
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

    
    // НЕ ИСПОЛЬЗУЕТСЯ
    //сгенерировать изображение для фона профиля
    public function generateBackgroundImage(Request $request)
    {
        $tracks = System::getUserLibraryJson("tracks", $request);

        if($tracks != false)
        {
            $lenOfTracks = count($tracks); //длина массива tracks

            $canvas = Image::canvas(1792,512, "#174668"); //"холст" на который будут добавляться обложки
            $x = 0; //смещение по оси x
            $y = 0; //по оси y

            //всего на холсте должно быть 14 обложек, поэтому цикл на 14 раз
            for($i = 0; $i <= 14; $i++)
            {   
                //выбирает рандомную обложку из списка треков
                $randNum = rand(0,$lenOfTracks - 1);
                $coverUrl = $tracks[$randNum]->album->images[count($tracks[$i]->album->images) - 1]->url;

                $cover = Image::make($coverUrl)->resize(256,256); //изменение размера на 256х256
                
                $canvas->insert($cover, "top-left", $x, $y); //вставкаи обложки на "холст"

                $x += 256;

                if($i === 7)
                {
                    $x = 0;
                    $y += 256;
                }
            }

            //сохранение 
            $folderName = $request->cookie('rand_name');
            $url = storage_path("app/public/user_libraries/" . $folderName . "/" . "bg_image.jpg");
            $canvas->save($url);
            $urlForImg = "storage/user_libraries/" . $folderName . "/" . "bg_image.jpg";

            return response()->json($urlForImg);
        }
        else
        { return response()->json(false); }
    }
}
