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
    
                //возвращаем страницу админку
                return view('/admin/controlPanel', compact('siteInfo')); 
            }
            else
            { return response()->json(false); }
        }
    }
}
