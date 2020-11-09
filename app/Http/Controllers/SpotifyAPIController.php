<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SpotifyWebAPI;
use Carbon\Carbon;

class SpotifyAPIController extends Controller
{
    //домашняя страница сайта
    public function home_page(Request $request)
    {   
        $spotify_access_token = $request->cookie('spotify_access_token');

        //если токен существует
        if($spotify_access_token != null)
        {
            $spotify_access_expiration = $request->cookie('spotify_access_expiration');
            
            if($spotify_access_expiration < Carbon::now())
            {
                //update token
                echo "update token";
            }
            else
            {
                $api = config('api');
                $api->setAccessToken($spotify_access_token);
                
                $spotify_username = $api->me()->display_name;
                $spotify_user_tracks = $api->getMySavedTracks(['offset' => 20]);
                $array = ['logged_in'=>true, 'spotify_username' => $spotify_username , 'spotify_user_tracks' => $spotify_user_tracks->items];

                return response()->json($array);
            }
        }   
        else
        { return response()->json($array = ['logged_in'=>false]); }
        
    }
}
