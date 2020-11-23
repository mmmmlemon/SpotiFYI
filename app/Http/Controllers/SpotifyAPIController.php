<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Globals\Globals;
use SpotifyWebAPI;
use Carbon\Carbon;
use File;
use Storage;    
use Illuminate\Contracts\Filesystem\FileNotFoundException; 

//запросы к Spotify API
class SpotifyAPIController extends Controller
{
    //домашняя страница сайта
    public function getHomePageUserTracksCount(Request $request)
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

    //получить библиотеку пользователя целиком
    public function getSpotifyUserLibrary(Request $request)
    {
        $checkToken = Globals::checkSpotifyAccessToken($request);

        if($checkToken != false)
        {
            $api = config('spotify_api');
            $options = ['limit' => 50, 'offset' => 0];

            //получаем все треки
            $spotifyMyTracks = $api->getMySavedTracks($options)->items;
            //получаем все альбомы
            $spotifyMyAlbums = $api->getMySavedAlbums($options)->items;
            //получаем все подписки
            $spotifyMyArtists = $api->getUserFollowedArtists($options)->artists->items;

            $spotifyUserTracks = [];
            $spotifyUserAlbums = [];
            $spotifyUserArtists = [];

            //треки
            while(count($spotifyMyTracks) > 0)
            {
                foreach($spotifyMyTracks as $item)
                { array_push($spotifyUserTracks, $item->track); }

                $options['offset'] += 50;
                $spotifyMyTracks = $api->getMySavedTracks($options)->items;
            }
        
            // альбомы
            while(count($spotifyMyAlbums) > 0)
            {
                foreach($spotifyMyAlbums as $item)
                { array_push($spotifyUserAlbums, $item->album); }

                $options['offset'] += 50;
                $spotifyMyAlbums = $api->getMySavedAlbums($options)->items;
            }

            //подписки
            while(count($spotifyMyArtists) > 0)
            {
                foreach($spotifyMyArtists as $item)
                { array_push($spotifyUserArtists, $item); }

                $options['after'] = $item->id;
                $spotifyMyArtists = $api->getUserFollowedArtists($options)->artists->items;
            }

            //сохраняем библиотку в json файлы
            //проверяем что папка user_libraries существует
            $check = File::exists(storage_path("app/public/user_libraries"));

            //если папки нет, то создаем её
            if($check != true)
            { Storage::disk('public')->makeDirectory("user_libraries"); }
            else
            {   
                //имя папки
                $folderName = $request->cookie('rand_name');

                if(Storage::disk('public')->makeDirectory("user_libraries/" . $folderName))
                {   
                    //сохраняем треки
                    File::put(storage_path("app/public/user_libraries/" . $folderName . "/" . "tracks.json"), json_encode($spotifyUserTracks));
                    //сохраняем альбомы
                    File::put(storage_path("app/public/user_libraries/" . $folderName . "/" . "albums.json"), json_encode($spotifyUserAlbums));
                    //сохраняем подписки
                    File::put(storage_path("app/public/user_libraries/" . $folderName . "/" . "artists.json"), json_encode($spotifyUserArtists));
                }
                else { return response()->json(false); }
            }

            return response()->json(true);
        }
        else
        { return false; }
    }

    //посчитать количество треков в библиотеке пользователя и вывести последние пять
    public function getSpotifyTracks(Request $request)
    {   
        //открываем файл треков
        $tracks = Globals::getUserLibraryJson("tracks", $request);
        if($tracks != false)
        {
            $lastFive = [];
            for($i = 0; $i < 5; $i++)
            {
                $name = Globals::getFullName($tracks[$i]);

                array_push($lastFive, ['id' => $tracks[$i]->id,
                                        'cover' => $tracks[$i]->album->images[count($tracks[$i]->album->images) - 1]->url,
                                        'name' => $name,
                                        'url' => $tracks[$i]->external_urls->spotify]);
            }
    
            //получаем кол-во треков и последние 5 треков
            $array = ['count' => count($tracks), 'last_five' => $lastFive];

            return response()->json($array);
        }
    }

    //посчитать кол-во альбомов и получить последние пять
    public function getSpotifyAlbums(Request $request)
    {
        //открываем файл альбомов
        $albums = Globals::getUserLibraryJson("albums", $request);

        if($albums != false)
        {
            $lastFive = [];
            for($i = 0; $i < 5; $i++)
            {
      
                $name = Globals::getFullName($albums[$i]);
                array_push($lastFive, ['id' => $albums[$i]->id,
                                        'cover' => $albums[$i]->images[count($albums[$i]->images) - 1]->url,
                                        'name' => $name,
                                        'url' => $albums[$i]->external_urls->spotify]);
            }
    
            //получаем кол-во треков и последние 5 треков
            $array = ['count' => count($albums), 'last_five' => $lastFive];
            
            return response()->json($array);
        }
    }


    //получить подписки и случайные пять
    public function getSpotifyArtists(Request $request)
    {
        //открываем файл подписок
        $artists = Globals::getUserLibraryJson("artists", $request);

        if($artists != false)
        {
            $randomFive = [];
        
            //использованные индексы элемента массива
            $usedNumbers = [];
    
            //пока не наберется 5 исполнителей
            while(count($randomFive) <= 4)
            {   
                //генерим рандомное числов, это будет индекс исполнителя в массиве с ними
                $randomNumber = rand(0,count($artists) - 1);
                //если индекс еще не был использован
                if(array_search($randomNumber, $usedNumbers) === false)
                {   
                    //добавляем индекс в массив и добавляем исполнителя
                    array_push($usedNumbers, $randomNumber);
                    array_push($randomFive, ['name' => $artists[$randomNumber]->name,
                                                'cover' => $artists[$randomNumber]->images[count($artists[$randomNumber]->images)-1]->url,
                                                'url' => $artists[$randomNumber]->external_urls->spotify,
                                                'id' => $artists[$randomNumber]->id]);
                }
            }   
    
            //получаем кол-во подписок и случайные пять
            $array = ['count' => count($artists), 'random_five' => $randomFive];
            
            return response()->json($array);
        }

    }

     //посчитать общее время всех треков
    public function getUserLibraryTime(Request $request)
    {
        //получаем все треки
        $tracks = Globals::getUserLibraryJson("tracks", $request);

        if($tracks != false)
        {    
            $overallMinutes = 0;
            $overallSeconds = 0;

            //считаем общее кол-во минут всех треков
            foreach($tracks as $track)
            {
                $overallMinutes += round($track->duration_ms / 60000);
            }

            $overallHours = floor($overallMinutes / 60); //кол-во часов
            $overallDays = floor($overallHours / 24); //кол-во дней
            $overallMonths = floor($overallDays / 30); //кол-во месяцев

            //вычисляем какое слово нужно подставить в конец (1 минуТА, 2 минуТЫ и т.п)
            $overallMinutes .= Globals::pickTheWord($overallMinutes, "минут", "минута", "минуты");

            if($overallHours > 0)
            {  $overallHours .= Globals::pickTheWord($overallHours, "часов", "час", "часа"); }
            
            if($overallDays > 0)
            {  $overallDays .= Globals::pickTheWord($overallDays, "дней", "день", "дня"); }
            
            if($overallMonths > 0)
            {  $overallMonths .= Globals::pickTheWord($overallMonths, "месяцев", "месяц", "месяца"); }

            return response()->json(['overallMinutes' => $overallMinutes, 'overallHours' => $overallHours,
                                    'overallDays' => $overallDays, 'overallMonths' => $overallMonths]);
        }
    }

    //пять самых длинных треков
    public function getFiveLongestTracks(Request $request)
    {
          //получаем все треки
          $tracks = Globals::getUserLibraryJson("tracks", $request);

          if($tracks != false)
          {  

            //выбираем название трека, длину, id и обложку
            $tracksClean = [];

            foreach($tracks as $track)
            {
                $id = $track->id;
                $duration = $track->duration_ms;
                $cover = $track->album->images[count($track->album->images) - 1]->url;
                $name = Globals::getFullName($track);
                $url = $track->external_urls->spotify;
                array_push($tracksClean, ['id' => $id, 'duration' => $duration, 'cover' => $cover, 'name' => $name, 'url' => $url]);
            }

            //сортировка по убыванию по ключу duration (длительность)
            usort($tracksClean, function ($a, $b){
                return strnatcmp( $b['duration'], $a['duration']);
            });

            //берем верхние пять элементов
            $topFive = array_slice($tracksClean, 0, 5, true);
            $newTopFive = [];
            foreach($topFive as $track)
            {
                //вычисляем длину трека в минутах и секундах
                $durationMs = $track['duration'];
                $durationS = $durationMs / 1000;
                $durationMinutes = round($durationS / 60, 3);
                
                $durationSeconds = floor(60 * ($durationMinutes - floor($durationMinutes)));

                $minutesStr = strval(floor($durationMinutes));
                $secondsStr = strval($durationSeconds);
                
                if(strlen($secondsStr) == 1)
                { $secondsStr = "0" . $secondsStr; }

                $durationStr = $minutesStr.":".$secondsStr;

                array_push($newTopFive, ['id' => $track['id'], 'duration' => $durationStr, 
                                        'cover' => $track['cover'], 'name' => $track['name'], 'url' => $track['url']]);
            }

            return response()->json($newTopFive);

          }
    }


}
