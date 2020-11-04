<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use SpotifyWebAPI;

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
            $api = new SpotifyWebAPI\SpotifyWebAPI();
            $api->setAccessToken($spotify_access_token);
            $spotify_profile = ['display_name' => $api->me()->display_name, 'avatar' => $api->me()->images[0]->url];
        }
        
        return view('vue_router', compact('access_check', 'spotify_profile'));
    }
}
