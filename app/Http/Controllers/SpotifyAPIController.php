<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Globals\Globals;
use SpotifyWebAPI;
use Carbon\Carbon;
use File;
use Storage;    
use Image;
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

            //получить случайное изображение с исполнителем
            $artistImageUrl = "";

            $randNum = rand(0, count($tracks) - 1);

            $randomArtistId = $tracks[$randNum]->id;

            $checkToken = Globals::checkSpotifyAccessToken($request);

            if($checkToken != false)
            {
                //получаем api
                $api = config('spotify_api');
                $coverImageUrl = $api->getTrack($randomArtistId)->album->images[0]->url;
            }   

            $response = ['overallMinutes' => $overallMinutes, 'overallHours' => $overallHours,
                        'overallDays' => $overallDays, 'overallMonths' => $overallMonths, 
                        'coverImageUrl' => $coverImageUrl];

            return response()->json($response);
        }
        else
        { return response()->json(false); }
    }

    //пять самых длинных треков
    public function getFiveLongestAndShortestTracks(Request $request)
    {
        //ф-ция, подсчет времени длительности треков
        function countTime($array)
        {
            $newArray = [];

            foreach($array as $track)
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

                array_push($newArray, ['id' => $track['id'], 'duration' => $durationStr, 
                                        'cover' => $track['cover'], 'name' => $track['name'], 'url' => $track['url']]);
            }

            return $newArray;
        }

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
            
            //треки отсортированние по убыванию и возрастанию
            $tracksDesc = Globals::sortArrayByKey($tracksClean, 'duration', 'desc');
            $tracksAsc = Globals::sortArrayByKey($tracksClean, 'duration', 'asc');

            //берем верхние пять элементов
            $topFiveDesc = array_slice($tracksDesc, 0, 5, true);
            $topFiveAsc = array_slice($tracksAsc, 0, 5, true);

            //подсчет времени длительности треков
            $topFiveDesc = countTime($topFiveDesc);
            $topFiveAsc = countTime($topFiveAsc);

            return response()->json(['fiveLongest' => $topFiveDesc, 'fiveShortest' => $topFiveAsc]);
        }
    }

    //найти среднюю длину трека
    public function getAverageLengthOfTrack(Request $request)
    {
        $tracks = Globals::getUserLibraryJson("tracks", $request);

        if($tracks != null)
        {
            $durationMs = []; //получаем массив с длиной треков в миллисекундах
            
            foreach($tracks as $track)
            { array_push($durationMs, $track->duration_ms); }
    
            $durationMn = [];   //получаем массив с длиной треков в минутах (округленной вниз)
    
            foreach($durationMs as $item)
            { array_push($durationMn, intval(floor($item / 60000))); }
            
            $countDurations = array_count_values($durationMn);  //считаем моду
            $mode = array_search(max($countDurations), $countDurations);
            
            $response = $mode . Globals::pickTheWord($mode, "минут", "минута", "минуты");

            return response()->json($response);
        }
        else
        { return response()->json(false); }
 
    }

    //сгенерировать изображение для фона профиля (ВЫРЕЗАТЬ И ИСПОЛЬЗОВАТЬ В ДРУГОМ МЕСТЕ)
    public function generateBackgroundImage(Request $request)
    {
        $tracks = Globals::getUserLibraryJson("tracks", $request);

        if($tracks != null)
        {
            $lenOfTracks = count($tracks); //длина массива tracks

            $canvas = Image::canvas(1792,512, "#174668"); //"холст" на который будут добавляться обложки
            $x = 0; //смещение по оси x
            $y = 0; //по оси y

            //всего на холсте должно быть 14 обложек, поэтому цикл на 14 раз
            for($i = 0; $i <= 14; $i++)
            {   
                //выбирает рандомную обложку из списка треков
                $randNum = rand(0,$lenOfTracks - 1);
                $coverUrl = $tracks[$randNum]->album->images[count($tracks[$i]->album->images) - 1]->url;

                $cover = Image::make($coverUrl)->resize(256,256); //изменение размера на 256х256
                
                $canvas->insert($cover, "top-left", $x, $y); //вставкаи обложки на "холст"

                $x += 256;

                if($i === 7)
                {
                    $x = 0;
                    $y += 256;
                }
            }

            //сохранение 
            $folderName = $request->cookie('rand_name');
            $url = storage_path("app/public/user_libraries/" . $folderName . "/" . "bg_image.jpg");
            $canvas->save($url);
            $urlForImg = "storage/user_libraries/" . $folderName . "/" . "bg_image.jpg";

            return response()->json($urlForImg);
        }
    }

    //получить любимые жанры пользователя
    public function getFavoriteGenres(Request $request)
    {   
        $checkToken = Globals::checkSpotifyAccessToken($request);

        if($checkToken != false)
        {
            //получаем api
            $api = config('spotify_api');

            $offset = 0;
            //подсчет жанров
            $genresCount = [];

            while($offset < 50)
            {
                //получаем треки
                $tracks = $api->getMyTop('tracks', ['limit' => 49, 'time_range' => 'short_term', 'offset' => $offset])->items;

                if($tracks != null)
                {   
                    $artistsArray = []; //массив для исполнителей
                    $genresArray = []; //массив для жанров
                    
                    //получаем id всех имеющихся в библиотеке исполнителей через треки
                    foreach($tracks as $track)
                    {
                        foreach($track->artists as $artist)
                        {
                            if(array_search($artist->id, $artistsArray) == false)
                            { array_push($artistsArray, $artist->id); }
                        }   
                    }

                    //читаем массив с исполнителями и записываем жанры
                    for($i = 0; $i < count($artistsArray); $i++)
                    {
                        $genres = $api->getArtist($artistsArray[$i]);

                        foreach($genres->genres as $genre)
                        { array_push($genresArray, $genre); }
                    }
                    
                    foreach($genresArray as $item)
                    {   
                        $findItem = array_key_exists($item, $genresCount);
                        
                        if($findItem == false)
                        { $genresCount[$item] = 1; }
                        else
                        { $genresCount[$item] += 1; }
                    }
                    $offset += 49;

                }
                else
                { return response()->json(false); }
            }

            //сортировка по убыванию
            arsort($genresCount);
            //топ 10
            $topTenGenres = array_slice($genresCount, 0, 10, true);

            return response()->json($topTenGenres);
        }
        else
        { return response()->json(false); }

    }

    //посчитать кол-во исполнителей
    public function getUniqueArtists(Request $request)
    {
        $tracks = Globals::getUserLibraryJson("tracks", $request);

        if($tracks != null)
        {
            $artistsArray = []; //массив для исполнителей

            foreach($tracks as $track)
            {
                foreach($track->artists as $artist)
                {
                    if(array_search($artist->id, $artistsArray) == false)
                    { array_push($artistsArray, $artist->id); }
                }   
            }

            $count = count(array_unique($artistsArray));

            $randArtistId = $artistsArray[rand(0, $count - 1)];

            $countArtists = $count . Globals::pickTheWord($count, "различных исполнителей", "исполнителя", "разных исполнителей");
            
            //получить случайное изображение с исполнителем 
            $artistImageUrl = "";

            $checkToken = Globals::checkSpotifyAccessToken($request);

            if($checkToken != false)
            {   
                //получаем api
                $api = config('spotify_api');
                $artistImageUrl = $api->getArtist($randArtistId)->images[0]->url;
            }

            $response = ['countArtists' => $countArtists, 'artistImageUrl' => $artistImageUrl];

            return response()->json($response);
        }
        else
        { return response()->json(false); }
    }

    //посчитать года и десятилетия
    public function getYearsAndDecades(Request $request)
    {
        $tracks = Globals::getUserLibraryJson("tracks", $request);

        if($tracks != null)
        {       
            //все даты
            $allYears = [];

            //запись всех годов в массив
            foreach($tracks as $track)
            {
                $year = $track->album->release_date;

                if($track->album->release_date_precision == "day" || $track->album->release_date_precision == "month")
                { $year = substr($track->album->release_date, 0, 4); }

                array_push($allYears, $year); 
            }

            //подсчет кол-ва всех годов
            $countYears = [];

            foreach($allYears as $year)
            {   
                $findYear = array_key_exists($year, $countYears);
                if($findYear == false)
                { $countYears[$year] = 1; }
                else
                { $countYears[$year] += 1; }
            }

            //сортировка годов по возрастанию
            ksort($countYears);

            //добавление недостающих годов в промежутке между самым старым и самым новым годом для линейного графика
            $yearMin =  array_keys($countYears)[0];
            $yearMax = array_keys($countYears)[count($countYears) - 1];

            //если года нет в массиве, то добавляем его со значением - 0
            for($i = $yearMin; $i <= $yearMax; $i++)
            {   
                $findIndex = array_key_exists($i, $countYears);
                if($findIndex == false)
                { $countYears[$i] = 0; }
            }

            //еще раз сортируем массив с годами
            ksort($countYears);

            //подсчет кол-ва всех годов
            $countDecades = [];

            foreach($allYears as $year)
            {   
                $decade = intval(substr($year, 0, 3) . "0");
                $findDecade = array_key_exists($decade , $countDecades);
                if($findDecade == false)
                { $countDecades[$decade ] = 1; }
                else
                { $countDecades[$decade ] += 1; }
            }
            
            //сортируем десятилетия по возрастанию
            ksort($countDecades);

            $response = ['countYears' => $countYears, 'countDecades' => $countDecades];

     


            //генерируем цвета для графиков
            //цвета для графика десятилетий
            $decadeColors = [];
            $offset = 0;
            for($i = 0; $i < 10; $i++)
            {   
                array_push($decadeColors, Globals::randomHslColor(['offset' => $offset]));
                $offset+=30;
            }

            $response['decadeColors'] = $decadeColors;

            //цвета для графика годов
            //каждый год окрашивается в тот же цвет что и десятилетие в графике десятилетий
            $yearColors = [];
            $years = array_keys($countYears);
            $previousDecade = substr(strval($years[0]), 0, 3);
            $indexOfColor = 0;
            foreach($years as $year)
            {
                $currentDecade = substr(strval($year), 0, 3);
                
                if($currentDecade != $previousDecade)
                {   
                    $indexOfColor++;
                    $previousDecade = $currentDecade;
                }

                array_push($yearColors, $decadeColors[$indexOfColor]);                
            }

            $response['yearColors'] = $yearColors;

            //год и десятилетие с наибольшим кол-вом песен
            arsort($countYears);
            $maxYear = key($countYears);
            arsort($countDecades);
            $maxDecade = key($countDecades);
            
            $response['maxYear'] = $maxYear;
            $response['maxDecade'] = $maxDecade;

            $maxDecadeSongs = $countDecades[key($countDecades)] . Globals::pickTheWord($countDecades[key($countDecades)], "песен", "песня", "песни");
            $maxYearSongs = $countYears[key($countYears)] . Globals::pickTheWord($countYears[key($countYears)], "песен", "песня", "песни");

            $response['maxDecadeSongs'] = $maxDecadeSongs;
            $response['maxYearSongs'] = $maxYearSongs;

            return response()->json($response);
        }   
        else
        { return response()->json(false); }
    }


}
