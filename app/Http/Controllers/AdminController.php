<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Validator;
use App;
use Image;
use Storage;
use File;

//AdminController
//админка и все что её касается
class AdminController extends Controller
{
    //viewControlPanel
    //вывести главную страницу контрольной панели
    //параметры: реквест
    //возвращает главную страницу админки
    public function viewControlPanel(Request $request)
    {
        //проверяем авторизован ли админ
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
    
                //возвращаем админку
                return view('/admin/controlPanel', compact('siteInfo', 'basicSettings')); 
            }
            else
            { return response()->json(false); }
        }
        //если нет, то 403
        else
        { abort(403); }
    }

    //viewLogoAndImages
    //вывести страницу редактирования лого и изображений
    //параметры: реквест
    //возвращает страницу админки
    public function viewLogoAndImages(Request $request)
    {
        //проверяем авторизацию
        if(Auth::check())
        {
            $settings = config('settings'); 

            if($settings != null)
            {
                //настройки сайта
                $siteInfo = ['siteLogo' => $settings->logo_img, 'siteTitle' => $settings->site_title];

                $images = ['logo' => asset($settings->logo_img),
                           'home_img' => asset($settings->home_img),
                           'user_img' => asset($settings->user_img)]; 

                //возвращаем админку
                return view('/admin/logoAndImg', compact('siteInfo', 'images')); 
            }
            else
            { return response()->json(false); }
        }
        //если не авторизован, то 403
        else
        { abort(403); }
    }

    //viewSiteInfo
    //вывести страницу редактирования информации о сайте (about и powered_by)
    //параметры: реквест
    //возвращает страницу админки
    public function viewSiteInfo(Request $request)
    {   
        //проверяем авторизацию
        if(Auth::check())
        {
            $settings = config('settings'); 

            if($settings != null)
            {
                //настройки сайта
                $siteInfo = ['siteLogo' => $settings->logo_img, 
                             'siteTitle' => $settings->site_title];

                $info = ['about' => $settings->about, 
                         'welcome' => $settings->welcome,
                         'poweredBy' => $settings->poweredBy]; 

                //возвращаем админку
                return view('/admin/siteInfo', compact('siteInfo', 'info')); 
            }
            else
            { return response()->json(false); }
        }
        //если не авторизован, то 403
        else
        { abort(403); }
    }

    //viewFAQ
    //показать страницу редактирования FAQ сайта
    //параметры: реквест
    //возвращает страницу админки
    public function viewFAQ(Request $request)
    {
        //проверка авторизации
        if(Auth::check())
        {
            $settings = config('settings'); 

            if($settings != null)
            {
                //настройки сайта
                $siteInfo = ['siteLogo' => $settings->logo_img, 
                             'siteTitle' => $settings->site_title];

                $info = ['faq' => $settings->faq]; 

                //возвращаем админку
                return view('/admin/faq', compact('siteInfo', 'info')); 
            }
            else
            { return response()->json(false); }
        }
        //если не авторизован, то 403
        else
        { abort(403); } 
    }

    //viewContacts
    //показать страницу редактирования контактов
    //параметры: реквест
    //возвращает страницу админки
    public function viewContacts(Request $request)
    {
        //проверка авторизации
        if(Auth::check())
        {
            $settings = config('settings'); 

            if($settings != null)
            {
                //настройки сайта
                $siteInfo = ['siteLogo' => $settings->logo_img, 
                             'siteTitle' => $settings->site_title];

                $info = ['contacts' => $settings->contacts]; 

                //возвращаем админку
                return view('/admin/contacts', compact('siteInfo', 'info')); 
            }
            else
            { return response()->json(false); }
        }
        //если не авторизован, то 403
        else
        { abort(403); }
    }

    //сохранить общие настройки сайта
    public function saveBasicSettings(Request $request)
    {
        //проверка авторизации
        if(Auth::check())
        {
            //валидация
            $validated = Validator::make($request->all(),[
                'site_title' => 'string|max:10',
                'version' => 'string|max:5',
                'spotify_client_id' => 'string|max:32',
                'spotify_client_secret' => 'string|max:32',
                'spotify_redirect_uri' => 'string|max:200',
            ]);

            //если валидация не прошла, то редиректим назад с ошибкой
            if($validated->fails()) {
                return redirect()->back()
                            ->withErrors($validated)
                            ->withInput();
            }
            else
            {
                //сохраняем настройки
                $settings = App\Settings::all()[0];
                $settings->site_title = $request->site_title;
                $settings->spotify_client_id = $request->spotify_client_id;
                $settings->spotify_client_secret = $request->spotify_client_secret;
                $settings->spotify_redirect_uri = $request->spotify_redirect_uri;
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
                'logo' => 'image',
                'home_img' => 'image',
            ]);

            //если валидация не прошла, то редиректим назад с ошибкой
            if($validated->fails()) {
                return redirect()->back()
                            ->withErrors($validated)
                            ->withInput();
            }
            else
            {
                //проверяем что папка storage/../system существует
                $check = File::exists(storage_path("app/public/system"));

                //если папки нет, то создаем её
                if($check != true)
                { Storage::disk('public')->makeDirectory("system"); }


                $settings = App\Settings::all()[0];

                //сохранение лого
                if($request->logo != null)
                {
                    $logo = Image::make($request->logo);
                    $logo->resize(500,500);
                    $logo->save(storage_path('app/public/system/logo.png'));
                }
                
                //сохранение картинки для главной страницы
                if($request->home_img != null)
                {
                    $home = Image::make($request->home_img);
                    $home->resize(1280,798);
                    $home->save(storage_path('app/public/system/home.png'));
                }

                //сохранение стандартного юзерпика
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

    //сохранить информацию о сайте
    public function saveSiteInfo(Request $request)
    {
        if(Auth::check())
        {
            //валидация
            $validated = Validator::make($request->all(),[
                'welcome' => 'string|nullable',
                'poweredBy' => 'string|nullable',
                'about' => 'string|nullable',
            ]);


            //если валидация не прошла, то редиректим назад с ошибкой
            if($validated->fails()) {
                return redirect()->back()
                            ->withErrors($validated)
                            ->withInput();
            }
            else
            {
                $settings = App\Settings::all()[0];
                //сохранение about и powered_by
                if($request->welcome != null)
                { $settings->welcome = $request->welcome; }

                if($request->about != null)
                { $settings->about = $request->about; }

                if($request->poweredBy != null)
                { $settings->poweredBy = $request->poweredBy; }
            
                $settings->save();

                return redirect()->back();
            }
        }
        else
        { abort(403); }
    }

    //сохранить FAQ
    public function saveFAQ(Request $request)
    {
        if(Auth::check())
        {
            //валидация
            $validated = Validator::make($request->all(),[
                'faq' => 'string',
            ]);

            //если валидация не прошла, то редиректим назад с ошибкой
            if($validated->fails()) {
                return redirect()->back()
                            ->withErrors($validated)
                            ->withInput();
            }
            else
            {
                $settings = App\Settings::all()[0];

                //сохранение FAQ
                $settings->faq = $request->faq;

                $settings->save();

                return redirect()->back();
            }
        }
        else
        { abort(403); }
    }

    //сохранить контакты
    public function saveContacts(Request $request)
    {
        if(Auth::check())
        {
            //валидация
            $validated = Validator::make($request->all(),[
                'contacts' => 'string',
            ]);

            //если валидация не прошла, то редиректим назад с ошибкой
            if($validated->fails()) {
                return redirect()->back()
                            ->withErrors($validated)
                            ->withInput();
            }
            else
            {
                $settings = App\Settings::all()[0];

                //сохранение FAQ
                $settings->contacts = $request->contacts;

                $settings->save();

                return redirect()->back();
            }
        }
        else
        { abort(403); }
    }
}
