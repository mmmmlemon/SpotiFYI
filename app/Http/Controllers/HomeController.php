<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use SpotifyWebAPI;
use Carbon\Carbon;
use Cookie;
use URL;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    //vue router
    public function vue_router(Request $request)
    {
        $spotify_access_token = $request->cookie('spotify_access_token');

        //проверяем был ли записан access_token
        $access_check = false;
        if($spotify_access_token != null)
        { $access_check = true; }

        //получаем информацию о профиле из Spotify
        $spotify_profile = [];
        if($spotify_access_token != null)
        {   
            $current_time = Carbon::now();
            $accessExpire = $request->cookie('spotify_access_expiration');
            if($current_time > $accessExpire)
            {
                $session->refreshAccessToken($request->cookie('spotify_refresh_token'));
                Cookie::queue('spotify_access_token', $session->getAccessToken(), 60*24*30);
                $accessExpiration = Carbon::now()->addMinutes(50);
                Cookie::queue('spotify_access_expiration', $accessExpiration, 60*24*30);
                Cookie::queue('spotify_refresh_token', $session->getRefreshToken(), 60*24*30);
                return redirect(URL::current());
            }
            else
            {         
                $api = new SpotifyWebAPI\SpotifyWebAPI();
                $api->setAccessToken($spotify_access_token);
                $spotify_profile = ['display_name' => $api->me()->display_name, 'avatar' => $api->me()->images[0]->url];
            }
        }

        return view('vue_router', compact('access_check', 'spotify_profile'));
    }
}
