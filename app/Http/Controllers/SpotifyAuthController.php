<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SpotifyWebAPI;
use Cookie;

//авторизация в Spotify API и выход
class SpotifyAuthController extends Controller
{
    //Spotify - авторизация
    public function spotify_auth()
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
            ],
        ];

        header('Location: ' . $session->getAuthorizeUrl($options));
        die();
    }

    //Spotify - авторизация, callback
    public function spotify_auth_callback()
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
        
        //сохраняем токены в cookies (для теста)
        Cookie::queue('spotify_access_token', $accessToken, 60*24*30);
        Cookie::queue('spotify_refresh_token', $refreshToken, 60*24*30);

        //редирект на главную
        return redirect('/');
    }

    //Spotify - выход
    public function spotify_logout(Request $request)
    {
        //если кука существует, то удаляем её
        if($request->hasCookie('spotify_access_token') == true)
        {
            Cookie::queue(Cookie::forget('spotify_access_token'));
            if($request->hasCookie('spotify_refresh_token') == true)
            {
                Cookie::queue(Cookie::forget('spotify_refresh_token'));

                return redirect('/');
            }
            else
            { echo "Error: spotify_refresh_token cookie doesn't exist"; }            
        }
        else
        { echo "Error: spotify_access_token doesn't exist"; }
    }
}
