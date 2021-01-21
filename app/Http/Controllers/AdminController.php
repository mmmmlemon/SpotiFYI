<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

//админка и все что её касается
class AdminController extends Controller
{
    public function viewControlPanel(Request $request)
    {
        $check = "";

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
    }
}
