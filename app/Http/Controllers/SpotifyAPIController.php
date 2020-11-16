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
    public function getSpotifyUserTracksCount(Request $request)
    { 
        $checkToken = Globals::checkSpotifyAccessToken($request);

        if($checkToken != false)
        {
           $api = config('spotify_api');

           $spotifyUserTracksCount = 50;
           $offset = 0;
           $spotifyUserTracks = $api->getMySavedTracks(['limit'=> 50, 'offset' => $offset]);

           while($offset <= 100)
           {
                $spotifyUserTracksCount += count($spotifyUserTracks->items);
                $offset += 50;
                $spotifyUserTracks = $api->getMySavedTracks(['limit' => 50, 'offset' => $offset]);
           }

           return response()->json($spotifyUserTracksCount);
        }
        else
        { return false; }
    }

    //профиль пользователя - юзернейм
    public function getSpotifyUsername(Request $request)
    {
        $checkToken = Globals::checkSpotifyAccessToken($request);

        if($checkToken != false)
        {
            $api = config('spotify_api');
            return response()->json(['loggedIn' => true, 'spotifyUsername' => $api->me()->display_name]);
        }
        else
        {  return response()->json($array = ['logged_in' => false, 'spotifyUsername' => false]);  }
    }

    //получить общую информацию о пользователе
    public function getSpotifyProfile(Request $request)
    {
        $checkToken = Globals::checkSpotifyAccessToken($request);

        if($checkToken != false)
        {
            $api = config('spotify_api');
            $profile = $api->me();
            $array = ['spotifyUsername' => $profile->display_name, 'country' => "https://www.countryflags.io/" . $profile->country . "/flat/32.png", 
                        'profile_url' => $profile->external_urls->spotify, 'followers' => $profile->followers->total,
                        'avatar' => $profile->images[0]->url, "subscription" => "$profile->product"];
           
            return response()->json($array);
        }
        else
        { return false; }
    }

    //посчитать количество треков в библиотеке пользователя
    public function getSpotifyTrackCount(Request $request)
    {
        $checkToken = Globals::checkSpotifyAccessToken($request);

        if($checkToken != false)
        {
            $api = config('spotify_api');
            $options = ['limit' => 50, 'offset' => 0];
            $spotifyMyTracks = $api->getMySavedTracks($options)->items;
            $spotifyTrackCount = 0;
            
            while(count($spotifyMyTracks) > 0)
            {
                $options['offset'] += 50;
                $spotifyTrackCount += count($spotifyMyTracks);
                $spotifyMyTracks = $api->getMySavedTracks($options)->items;
            }

            return response()->json($spotifyTrackCount);
        }
        else
        { return false; }
    }

    //получить последние 5 треков
    public function getSpotifyLastFive(Request $request, $entity)
    {
        $checkToken = Globals::checkSpotifyAccessToken($request);

        if($checkToken != false)
        {
            $api = config('spotify_api');
            $options = ['limit' => 5, 'offset' => 0];

            $spotifyLastFive = [];

            if($entity == "tracks")
            { $spotifyLastFive = $api->getMySavedTracks($options)->items; }
            else if($entity == "albums")
            { $spotifyLastFive = $api->getMySavedAlbums($options)->items; }
            
            $lastFive = [];

            foreach($spotifyLastFive as $item)
            {   
                $current_item = [];

                if($entity == "tracks")
                { $current_item = $item->track; }
                else
                { $current_item = $item->album; }
                
                $artists = "";

                for($i = 1; $i <= count($current_item->artists); $i++)
                {
                    if($i != count($current_item->artists) && count($current_item->artists) > 1)
                    { $artists .= $current_item->artists[$i-1]->name . ", ";}
                    else
                    { $artists .= $current_item->artists[$i-1]->name; }

                    $artists .= " - " . $current_item->name;
                }
                
                $cover = "";

                if($entity == "tracks")
                {  $cover = $current_item->album->images[2]->url; }
                else if ($entity == "albums")
                {  $cover = $current_item->images[2]->url; }

                array_push($lastFive, ['cover' => $cover,
                                        'name' => $artists, 
                                        'url' => $current_item->external_urls->spotify,
                                        'id' => $current_item->id]);                       
            }

            return response()->json($lastFive);
        }
        else
        { return false; }
    }

    //посчитать количество альбомов в библиотеке
    public function getSpotifyAlbumCount(Request $request)
    {
        $checkToken = Globals::checkSpotifyAccessToken($request);

        if($checkToken != false)
        {
            $api = config('spotify_api');
            $options = ['limit' => 50, 'offset' => 0];
            $spotifyMyAlbums = $api->getMySavedAlbums($options)->items;
            $spotifyAlbumCount = 0;

            while(count($spotifyMyAlbums) > 0)
            {
                $options['offset'] += 50;
                $spotifyAlbumCount += count($spotifyMyAlbums);
                $spotifyMyAlbums = $api->getMySavedAlbums($options)->items;
            }

            return response()->json($spotifyAlbumCount);
        }
        else
        { return false; }
    }
}
