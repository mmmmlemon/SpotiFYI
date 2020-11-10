<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Globals\Globals;
use SpotifyWebAPI;
use Carbon\Carbon;

class SpotifyAPIController extends Controller
{
    //домашняя страница сайта
    public function home_page(Request $request)
    { 
        $checkToken = Globals::checkSpotifyAccessToken($request);

        if($checkToken != false)
        {
           $api = config('spotify_api');
           $spotifyUsername = $api->me()->display_name;
           $spotifyUserTracks = $api->getMySavedTracks();
           $array = ['loggedIn' => true, 'spotifyUsername' => $spotifyUsername, 'spotifyUserTracks' => $spotifyUserTracks];
           return response()->json($array);
        }
        else
        {
            return response()->json($array = ['logged_in' => false]);
        }
    }
}
