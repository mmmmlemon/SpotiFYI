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
    public function index()
    {
        return view('home');
    }

    //vue router
    public function vue_router(Request $request)
    {   
        //проверка токена
        $checkToken = Globals::checkSpotifyAccessToken($request);

        //если токен есть и он действительный
        if($checkToken != false)
        {
            $api = config('spotify_api');
            $spotify_profile = ['display_name' => $api->me()->display_name, 'avatar' => $api->me()->images[0]->url];
            return view('vue_router', compact('checkToken', 'spotify_profile'));
        }
        else //если токена нет или он не действительный
        {
            return view('vue_router', compact('checkToken'));
        }
    }
}
