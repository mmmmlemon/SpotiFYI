<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SpotifyWebAPI;
use Cookie;
use Carbon\Carbon;

//авторизация в Spotify API и выход
class SpotifyAuthController extends Controller
{
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
            ],
        ];

        header('Location: ' . $session->getAuthorizeUrl($options));
        die();
    }

    //Spotify - авторизация, callback
    //получает acces и refresh токены и записывает их в куки
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

        //сохраняем токены в cookies
        Cookie::queue('spotify_access_token', $accessToken, 60*24*30);
        Cookie::queue('spotify_access_expiration', $accessExpiration, 60*24*30);
        Cookie::queue('spotify_refresh_token', $refreshToken, 60*24*30);

        //редирект на главную
        return redirect('/');
    }

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
}
