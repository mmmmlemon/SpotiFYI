<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Globals\Globals;
use SpotifyWebAPI;
use Carbon\Carbon;

//запросы к Spotify API
class SpotifyAPIController extends Controller
{
    //домашняя страница сайта
    public function homePage(Request $request)
    { 
        $checkToken = Globals::checkSpotifyAccessToken($request);

        if($checkToken != false)
        {
           $api = config('spotify_api');
           $spotifyUsername = $api->me()->display_name;

           $spotifyUserTracksCount = 0;
           $offset = 0;
           $spotifyUserTracks = $api->getMySavedTracks(['limit'=> 50, 'offset' => $offset]);

           while(count($spotifyUserTracks->items) != 0)
           {
                $spotifyUserTracksCount += count($spotifyUserTracks->items);
                $offset += 50;
                $spotifyUserTracks = $api->getMySavedTracks(['limit' => 50, 'offset' => $offset]);
           }

           $array = ['loggedIn' => true, 'spotifyUsername' => $spotifyUsername, 'spotifyUserTracksCount' => $spotifyUserTracksCount];
           return response()->json($array);
        }
        else
        {
            return response()->json($array = ['logged_in' => false]);
        }
    }
}
