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
        // $this->middleware('auth');
    }


    
    //getNavigationSettings
    //получить настройки для навигации
    public function getNavigationSettings(Request $request){
        $settings = config('settings');
        //проверка токена
        $checkToken = System::checkSpotifyAccessToken($request);

        $spotifyProfile = null;
 
        if($checkToken != false){
            //вызываем api, получаем профиль пользователя
            $api = config('spotify_api');

            //если у пользователя установлена аватарка, то записываем ссылку на неё
            $avatarUrl = "";

            if(count($api->me()->images) > 0)
            { $avatarUrl = $api->me()->images[0]->url; }
            else
            {   
                //заглушка на случай если нет аватарки
                $avatarUrl = asset(config('settings')->user_img);
            }
            
            //пользователь
            $spotifyProfile = ['displayName' => $api->me()->display_name, 'avatar' => $avatarUrl];

 
        }

        $response = ['site_title' => $settings->site_title,
        'checkToken' => $checkToken,
        'spotifyProfile' => $spotifyProfile];

        return response()->json($response);

    }

    //index
    //главная страница сайта, отображает меню на верхней панели и vue-router под меню
    public function index(Request $request)
    {   
        $settings = config('settings'); 

        if($settings != null)
        {
            //настройки сайта для app.blade
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
                    //заглушка на случай если нет аватарки
                    $avatarUrl = asset(config('settings')->user_img);
                }
                
                //пользователь
                $spotifyProfile = ['displayName' => $api->me()->display_name, 'avatar' => $avatarUrl];

                //возвращаем главную страницу с профилем пользователя
                return view('index', compact('checkToken', 'spotifyProfile', 'siteInfo'));
            }
            else //если токена нет или он не действительный
            { return view('index', compact('checkToken', 'siteInfo')); }
        }
        else
        { return "Uncomment boot() in the AppServiceProvider.php"; }
    }

    //getWelcomeMessage
    //получить welcome msg
    public function getWelcomeMessage()
    {
        $settings = config('settings');

        if($settings != null)
        {
            return response()->json($settings->welcome);
        }
        else
        { return response()->json(false); }
    }

    //getSiteInfo
    //получить информацию о сайте из БД для страницы "О проекте"
    public function getSiteInfo()
    {
        $settings = config('settings');

        if($settings != null)
        {
            return response()->json(['siteTitle' => $settings->site_title, 
                                     'version' => $settings->version, 
                                     'poweredBy' => $settings->poweredBy]);
        }
        else
        { return response()->json(false); }
    }

    //getAbout
    //получить информацию о сайте, About
    public function getAbout()
    {
        $settings = config('settings');

        if($settings != null)
        {
            return response()->json($settings->about);
        }
        else
        { return response()->json(false); }
    }

    //getFAQ
    //получить информацию о сайте, FAQ
    public function getFAQ()
    {
        $settings = config('settings');

        if($settings != null)
        {
            return response()->json($settings->faq);
        }
        else
        { return response()->json(false); }
    }

    //getContacts
    //получить информацию о сайте, FAQ
    public function getContacts()
    {
        $settings = config('settings');

        if($settings != null)
        {
            return response()->json($settings->contacts);
        }
        else
        { return response()->json(false); }
    }

    //getSiteLogoUrl
    //получить логотип сайта
    public function getSiteLogoUrl()
    {   
        $settings = config('settings');

        if($settings != null)
        {
            return response()->json(asset($settings->logo_img));
        }
        else
        { return response()->json(false); }
    }

    //getHomePageImageUrl
    //получить фоновое изображение для домашней страницы
    public function getHomePageImageUrl()
    {
        $settings = config('settings');

        if($settings != null)
        {
            return response()->json(asset($settings->home_img));
        }
        else
        { return response()->json(false); } 
    }

    //getWelcomeImageUrl
    //получить изображение для приветствия
    public function getWelcomeImageUrl()
    {
        $settings = config('settings');

        if($settings != null)
        {
            return response()->json(asset($settings->welcome_img));
        }
        else
        { return response()->json(false); }
    }
}
