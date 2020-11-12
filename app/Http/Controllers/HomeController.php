<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use SpotifyWebAPI;
use Carbon\Carbon;
use Cookie;
use URL;
use App\Globals\Globals;

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


    //vue router
    public function vueRouter(Request $request)
    {   
        //проверка токена
        $checkToken = Globals::checkSpotifyAccessToken($request);

        //если токен есть и он действительный
        if($checkToken != false)
        {
            $api = config('spotify_api');
            $spotifyProfile = ['displayName' => $api->me()->display_name, 'avatar' => $api->me()->images[0]->url];
            return view('vue_router', compact('checkToken', 'spotifyProfile'));
        }
        else //если токена нет или он не действительный
        {
            return view('vue_router', compact('checkToken'));
        }
    }

    //получить название веб-сайта
    public function getSiteInfo()
    {
        $settings = config('settings');
        return response()->json(['siteTitle' => $settings->site_title, 
                                'version' => $settings->version, 
                                'aboutText' => $settings->about_text]);
    }
}
