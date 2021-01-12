<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use SpotifyWebAPI;
use Carbon\Carbon;
use Cookie;
use URL;
use App\Globals\System;

// HomeController
//ф-ции главной страницы, а так же ф-ции сайта не связанные с профилем пользователя и статистикой
class HomeController extends Controller
{
    public function __construct()
    {
        //
        // $this->middleware('auth');
    }

    //index
    //главная страница сайта, отображает меню на верхней панели и vue-router под меню
    public function index(Request $request)
    {   
        //настройки сайта для app.blade
        $settings = config('settings');
        $siteInfo = ['siteLogo' => $settings->logo_img, 'siteTitle' => $settings->site_title];

        //проверка токена
        $checkToken = System::checkSpotifyAccessToken($request);

        //если токен есть и он действительный
        if($checkToken != false)
        {
            //вызываем api, получаем профиль пользователя
            $api = config('spotify_api');

            //если у пользователя установлена аватарка, то записываем ссылку на неё
            $avatarUrl = "";

            if(count($api->me()->images) > 0)
            { $avatarUrl = $api->me()->images[0]->url; }
            else
            {   
                //временная заглушка на случай если нет аватарки
                //to do: добавить свою дефолтную аватарку
                $avatarUrl = "https://res.cloudinary.com/techsnips/image/fetch/w_2000,f_auto,q_auto,c_fit/https://adamtheautomator.com/content/images/size/w2000/2019/07/get-ad-users-from-text-file---user-2517433_960_720.png";
            }
            


            $spotifyProfile = ['displayName' => $api->me()->display_name, 'avatar' => $avatarUrl];
            //возвращаем главную страницу с профилем пользователя
            return view('index', compact('checkToken', 'spotifyProfile', 'siteInfo'));
        }
        else //если токена нет или он не действительный
        { return view('index', compact('checkToken', 'siteInfo')); }
    }

    //getSiteInfo
    //получить информацию о сайте из БД для страницы "О проекте"
    public function getSiteInfo()
    {
        $settings = config('settings');

        return response()->json(['siteTitle' => $settings->site_title, 
                                 'version' => $settings->version, 
                                 'poweredBy' => $settings->powered_by]);
    }

    //getSiteLogoUrl
    //получить логотип сайта
    public function getSiteLogoUrl(){
        
        $settings = config('settings');

        return response()->json(asset($settings->logo_img));
    }

    //getHomePageImageUrl
    //получить фоновое изображение для домашней страницы
    public function getHomePageImageUrl(){

        $settings = config('settings');

        return response()->json(asset($settings->home_img));
    }

    //getWelcomeImageUrl
    //получить изображение для приветствия
    public function getWelcomeImageUrl(){

        $settings = config('settings');

        return response()->json(asset($settings->welcome_img));
    }

}
