<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SpotifyWebAPI;
use Cookie;
use Carbon\Carbon;
use File;
use Storage;
use Illuminate\Http\Response;

//авторизация в Spotify API и выход
class SpotifyAuthController extends Controller
{
    //spotifyAuth
    //Spotify - авторизация
    //запрашивает авторизацию у Spotify API
    public function spotifyAuth()
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
                'user-library-read',
                'user-follow-read',
                'user-top-read',
                'user-read-recently-played',
            ],
        ];

        header('Location: ' . $session->getAuthorizeUrl($options));
        die();
    }

    //spotifyAuthCallback
    //Spotify - авторизация, callback
    //получает access и refresh токены и записывает их в куки
    public function spotifyAuthCallback()
    {
        $session = new SpotifyWebAPI\Session(
            config('settings')->spotify_client_id,
            config('settings')->spotify_client_secret,
            config('settings')->spotify_redirect_uri
        );
        
        //Запросить access token используя authorization code из url в браузере
        $session->requestAccessToken($_GET['code']);
        
        //получаем access и refresh токены
        $accessToken = $session->getAccessToken();
        $refreshToken = $session->getRefreshToken();
        $accessExpiration = Carbon::now()->addMinutes(50);
        $chars = "ABCDEFG";
        $randName = $chars[rand(0, 6)] . rand(0,9) . $chars[rand(0, 6)] . rand(0,9) . $chars[rand(0, 6)] . rand(0,9); //рандомное имя папки в которую будут сохраняться результаты

        //сохраняем токены в cookies
        Cookie::queue('spotify_access_token', $accessToken, 60*24*30);
        Cookie::queue('spotify_access_expiration', $accessExpiration, 60*24*30);
        Cookie::queue('spotify_refresh_token', $refreshToken, 60*24*30);
        //случайное имя папки куда будут записываться JSON'ы с библиотекой пользователя
        Cookie::queue('rand_name', $randName, 60*24*30); 

        //редирект на главную
        return redirect('/');
    }

    //spotifyLogout
    //Spotify - выход
    public function spotifyLogout(Request $request)
    {
        //если кука существует, то удаляем её
        if($request->hasCookie('spotify_access_token') == true)
        {
            Cookie::queue(Cookie::forget('spotify_access_token'));
            
            if($request->hasCookie('spotify_refresh_token') == true)
            {
                Cookie::queue(Cookie::forget('spotify_refresh_token'));
                Cookie::queue(Cookie::forget('spotify_access_expiration'));
                return redirect('/');
            }
            else
            { echo "Error: spotify_refresh_token cookie doesn't exist"; }            
        }
        else
        { echo "Error: spotify_access_token doesn't exist"; }
    }

    //cleanUserData
    //очистить пользовательские файлы
    public function cleanUserData(Request $request)
    {   
        //получаем имя папки с данными пользователя из Cookies
        $folderName = $request->cookie('rand_name');

        //проверяем что папка существует
        $check = File::exists(storage_path("app/public/user_libraries/".$folderName));

        if($check == true)
        {
            Storage::disk('public')->deleteDirectory("user_libraries/".$folderName);

            return response()->json(true);
        }
        else
        { return response()->json(false); }


    }

    //checkCookie
    //проверить cookies
    public function checkCookies(Request $request)
    {
        //если куки есть, то возвращаем false, чтобы видимость сообщения о куках тоже была false
        if($request->hasCookie('cookies_accepted') != false)
        {
            return response()->json(false);
        }
        else
        {   
            $response = new Response(true);

            $response->withCookie(cookie()->forever('cookies_accepted', true));

            return $response;
        }
    }

    //checkToken
    //проверка токена
    public function checkSpotifyToken(Request $request){

        $token = $request->cookie('spotify_access_token');

        if($token == null){
            return response()->json('noToken');
        } else{
            
            $tokenExpiration = $request->cookie('spotify_access_expiration');
            
            if(Carbon::now() >= $tokenExpiration){
                return response()->json('refreshToken');
            } else {
                return response()->json('yesToken');
            }
        }
    }
    
    //refreshToken
    public function refreshToken(Request $request){
        //получаем имя папки с данными пользователя из Cookies
        $folderName = $request->cookie('rand_name');

        //проверяем что папка существует
        $check = File::exists(storage_path("app/public/user_libraries/".$folderName));

        if($check == true)
        {
            Storage::disk('public')->deleteDirectory("user_libraries/".$folderName);

            return response()->json(true);
        }

        //создаем новую сессию Spotify API
        $session = new SpotifyWebAPI\Session(
            config('settings')->spotify_client_id,
            config('settings')->spotify_client_secret,
            config('settings')->spotify_redirect_uri
        );
        
        //задаем опции
        $options = [
            'scope' => [
                'playlist-read-private',
                'user-read-private',
                'user-library-read',
                'user-follow-read',
            ],
        ];

        //обновляем токен, рефреш токен и время действия токена
        $session->refreshAccessToken($request->cookie('spotify_refresh_token'));
        $newAccessToken = $session->getAccessToken();
        $newRefreshToken = $session->getRefreshToken();
        $accessExpiration = Carbon::now()->addMinutes(50);
        $chars = "ABCDEFG";
        $randName = $chars[rand(0, 6)] . rand(0,9) . $chars[rand(0, 6)] . rand(0,9) . $chars[rand(0, 6)] . rand(0,9);
        
        //записывем новые токены в куки
        Cookie::queue('spotify_access_token',  $newAccessToken, 60*24*30*12);
        Cookie::queue('spotify_refresh_token', $newRefreshToken, 60*24*30*12);
        Cookie::queue('spotify_access_expiration', $accessExpiration, 60*24*30*12);
        Cookie::queue('rand_name', $randName, 60*24*30);

        //устанавливаем новый токен в сессию
        config('spotify_api')->setAccessToken($newAccessToken);

        //возвращаем пользователя назад
        return redirect()->back();
    }
}
