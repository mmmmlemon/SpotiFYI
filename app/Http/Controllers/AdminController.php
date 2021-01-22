<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Validator;
use App;
use Image;
use Storage;

//админка и все что её касается
class AdminController extends Controller
{
    //вывести главную страницу контрольной панели
    public function viewControlPanel(Request $request)
    {
        if(Auth::check())
        {
            $settings = config('settings'); 

            if($settings != null)
            {
                //настройки сайта
                $siteInfo = ['siteLogo' => $settings->logo_img, 'siteTitle' => $settings->site_title];

                $basicSettings = ['siteTitle' => $settings->site_title,
                                  'version' => $settings->version,
                                  'contactEmail' => $settings->contact_email,
                                  'spotify_api_client_id' => $settings->spotify_client_id,
                                  'spotify_api_client_secret' => $settings->spotify_client_secret,
                                  'spotify_api_redirect_uri' => $settings->spotify_redirect_uri];
    
                //возвращаем страницу админку
                return view('/admin/controlPanel', compact('siteInfo', 'basicSettings')); 
            }
            else
            { return response()->json(false); }
        }
        else
        { abort(403); }
    }

    //вывести страницу редактирования лого и изображений
    public function viewLogoAndImages(Request $request)
    {
        if(Auth::check())
        {
            $settings = config('settings'); 

            if($settings != null)
            {
                //настройки сайта
                $siteInfo = ['siteLogo' => $settings->logo_img, 'siteTitle' => $settings->site_title];

                $images = ['logo' => asset($settings->logo_img),
                           'home_img' => asset($settings->home_img),
                           'welcome_img' => asset($settings->welcome_img),
                           'user_img' => asset($settings->user_img)];    
                //возвращаем страницу админку
                return view('/admin/logoAndImg', compact('siteInfo', 'images')); 
            }
            else
            { return response()->json(false); }
        }
        else
        { abort(403); }
    }

    //сохранить общие настройки сайта
    public function saveBasicSettings(Request $request)
    {
        if(Auth::check())
        {
            //валидация
            $validated = Validator::make($request->all(),[
                'site_title' => 'string|max:10',
                'version' => 'string|max:5',
                'contact_email' => 'email|max:50',
                'spotify_client_id' => 'string|max:32',
                'spotify_client_secret' => 'string|max:32',
                'spotify_redirect_uri' => 'string|max:200',
            ]);

            if ($validated->fails()) {
                return redirect()->back()
                            ->withErrors($validated)
                            ->withInput();
            }
            else
            {
                $settings = App\Settings::all()[0];

                $settings->site_title = $request->site_title;
                $settings->spotify_client_id = $request->spotify_client_id;
                $settings->spotify_client_secret = $request->spotify_client_secret;
                $settings->spotify_redirect_uri = $request->spotify_redirect_uri;
                $settings->contact_email = $request->contact_email;
                $settings->version = $request->version;

                $settings->save();

                return redirect()->back();
            }
        }
        else
        { abort(403); }
        
    }

    //сохранить лого и изображения
    public function saveLogoAndImages(Request $request)
    {
        if(Auth::check())
        {
            //валидация
            $validated = Validator::make($request->all(),[
 
            ]);

            if ($validated->fails()) {
                return redirect()->back()
                            ->withErrors($validated)
                            ->withInput();
            }
            else
            {
                $settings = App\Settings::all()[0];

                if($request->logo != null)
                {
                    $logo = Image::make($request->logo);
                    $logo->resize(500,500);
                    $logo->save(storage_path('app/public/system/logo.png'));
                }
                
                if($request->home_img != null)
                {
                    $home = Image::make($request->home_img);
                    $home->resize(1280,798);
                    $home->save(storage_path('app/public/system/home.png'));
                }

                if($request->welcome_img != null)
                {
                    $welcome = Image::make($request->welcome_img);
                    $welcome->resize(730,365);
                    $welcome->save(storage_path('app/public/system/welcome.png'));
                }

                if($request->user_img != null)
                {
                    $user = Image::make($request->user_img);
                    $user->resize(500,500);
                    $user->save(storage_path('app/public/system/user.png'));
                }

                $settings->save();

                return redirect()->back();
            }
        }
        else
        { abort(403); }
    }


}
