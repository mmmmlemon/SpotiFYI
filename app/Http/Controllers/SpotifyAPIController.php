<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Globals\System;
use App\Globals\Helpers;
use SpotifyWebAPI;
use Carbon\Carbon;
use File;
use Storage;    
use Image;
use Illuminate\Contracts\Filesystem\FileNotFoundException; 

//SpotifyAPIController
//функции связанные с запросами к Spotify API: получение бибилотеки пользователя, 
//подсчет кол-ва треков, анализ треков и т.п
//возвращает число - количество треков
class SpotifyAPIController extends Controller
{
    //getHomePageUserTracksCount
    //посчитать кол-во треков для вывода на главной странице сайта
    //параметры: реквест
    public function getHomePageUserTracksCount(Request $request)
    { 
        //проверка токена
        $checkToken = System::checkSpotifyAccessToken($request);

        if($checkToken != false)
        {
           //получаем api
           $api = config('spotify_api');

           $spotifyUserTracksCount = 0; //кол-во треков
           $offset = 0; //смещение
           //получаем первые 50 треков из библиотеки, со смещением 0
           $spotifyUserTracks = $api->getMySavedTracks(['limit'=> 50, 'offset' => $offset]); 

           //пока смещение меньше 100, прибавляем кол-во полученные треков 
           //и получаем следующие 50 с новым смещением
           //итого максимум будет 150 треков, в зависимости от кол-ва треков
           //будет выводиться разное сообщение на главной странице
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

    //getSpotifyUsername
    //получить юзернейм пользователя для вывода на главной странице
    //возвращает строку с никнеймом юзера
    //параметры: реквест
    public function getSpotifyUsername(Request $request)
    {
        $checkToken = System::checkSpotifyAccessToken($request);

        if($checkToken != false)
        {
            $api = config('spotify_api');
            return response()->json($api->me()->display_name);
        }
        else
        {  return response()->json(false);  }
    }

    //getSpotifyProfile
    //получить общую информацию из профиля пользователя
    //возвращает JSON с информацией о профиле
    //параметры: реквест
    public function getSpotifyProfile(Request $request)
    {   
        //проверка токена
        $checkToken = System::checkSpotifyAccessToken($request);

        if($checkToken != false)
        {
            $api = config('spotify_api');
            $profile = $api->me();
            $response = ['spotifyUsername' => $profile->display_name, 'country' => "https://www.countryflags.io/" . $profile->country . "/flat/32.png", 
                        'profile_url' => $profile->external_urls->spotify, 'followers' => $profile->followers->total,
                        'avatar' => $profile->images[0]->url, "subscription" => "$profile->product"];
           
            return response()->json($response);
        }
        else
        { return false; }
    }

    //getSpotifyUserLibrary
    //получить библиотеку пользователя целиком (треки, альбомы и подписки)
    //информация о библиотеке записывается в JSON файлы которые хранятся в storage
    //TO-DO - файлы удаляются после окончания сессии пользователя 
    //возвращает true, если все файлы успешно были записаны, или false если нет
    //параметры: реквест
    public function getSpotifyUserLibrary(Request $request)
    {
        //проверяем токен
        $checkToken = System::checkSpotifyAccessToken($request);

        if($checkToken != false)
        {
            $api = config('spotify_api');
            //опции для работы со Spotify API, лимит записей 50 (максимальный) и сдвиг
            $options = ['limit' => 50, 'offset' => 0];

            //получаем первые 50 треков
            $spotifyMyTracks = $api->getMySavedTracks($options)->items;
            //получаем первые 50 альбомов
            $spotifyMyAlbums = $api->getMySavedAlbums($options)->items;
            //получаем первые 50 подписок
            $spotifyMyArtists = $api->getUserFollowedArtists($options)->artists->items;

            //массивы для полного списка треков, альбомов и подписок
            $spotifyUserTracks = [];
            $spotifyUserAlbums = [];
            $spotifyUserArtists = [];

            //получаем все треки
            while(count($spotifyMyTracks) > 0)
            {
                foreach($spotifyMyTracks as $item)
                { array_push($spotifyUserTracks, $item->track); }

                $options['offset'] += 50;
                $spotifyMyTracks = $api->getMySavedTracks($options)->items;
            }
        
            //получаем все альбомы
            while(count($spotifyMyAlbums) > 0)
            {
                foreach($spotifyMyAlbums as $item)
                { array_push($spotifyUserAlbums, $item->album); }

                $options['offset'] += 50;
                $spotifyMyAlbums = $api->getMySavedAlbums($options)->items;
            }

            //получаем все подписки
            while(count($spotifyMyArtists) > 0)
            {
                foreach($spotifyMyArtists as $item)
                { array_push($spotifyUserArtists, $item); }

                $options['after'] = $item->id;
                $spotifyMyArtists = $api->getUserFollowedArtists($options)->artists->items;
            }

            //сохраняем библиотку в JSON файлы
            //проверяем что папка user_libraries существует
            $check = File::exists(storage_path("app/public/user_libraries"));

            //если папки нет, то создаем её
            if($check != true)
            { Storage::disk('public')->makeDirectory("user_libraries"); }
            else
            {   
                //получаем из Cookies имя папки в которую будут сохраняться JSON'ы
                $folderName = $request->cookie('rand_name');

                //создаем эту папку, и если он создалась то записываем содержимое массивов в файлы
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

    // ОБЩЕЕ

    //getSpotifyTracks
    //посчитать количество треков в библиотеке пользователя и вывести последние пять
    //возвращает JSON с кол-вом треков и последние 5 треков в библиотеке пользователя
    //параметры: реквест
    public function getSpotifyTracks(Request $request)
    {   
        //открываем файл треков из storage
        $tracks = System::getUserLibraryJson("tracks", $request);

        //если файл есть
        if($tracks != false)
        {
            //записываем в массив последние пять треков
            $lastFive = [];
            for($i = 0; $i < 5; $i++)
            {   
                //получаем полное название трека
                $name = Helpers::getFullNameOfItem($tracks[$i]);

                array_push($lastFive, ['id' => $tracks[$i]->id,
                                        'cover' => $tracks[$i]->album->images[count($tracks[$i]->album->images) - 1]->url,
                                        'name' => $name,
                                        'url' => $tracks[$i]->external_urls->spotify]);
            }
    
            //записываем в массив кол-во треков и последние 5 треков
            $response = ['count' => count($tracks), 'last_five' => $lastFive];

            return response()->json($response);
        }
    }

    //getSpotifyAlbums
    //посчитать количество альбомов в библиотеке пользователя и вывести последние пять
    //возвращает JSON с кол-вом альбомов и последние 5 альбомов в библиотеке пользователя
    //параметры: реквест
    public function getSpotifyAlbums(Request $request)
    {
        //открываем файл альбомов
        $albums = System::getUserLibraryJson("albums", $request);

        //если он есть
        if($albums != false)
        {   
            //записываем в массив последние пять альбомов
            $lastFive = [];
            for($i = 0; $i < 5; $i++)
            {
                //получаем полное название альбома
                $name = Helpers::getFullNameOfItem($albums[$i]);
                array_push($lastFive, ['id' => $albums[$i]->id,
                                        'cover' => $albums[$i]->images[count($albums[$i]->images) - 1]->url,
                                        'name' => $name,
                                        'url' => $albums[$i]->external_urls->spotify]);
            }
    
            //записыаем кол-во альбомов и последние 5 альбомов
            $response = ['count' => count($albums), 'last_five' => $lastFive];
            
            return response()->json($response);
        }
    }


    //getSpotifyArtists
    //посчитать подписки на исполнителей в библиотеке пользователя и вывести случайные пять
    //возвращает JSON с кол-вом подписок и случайные 5 подписок пользователя
    //параметры: реквест
    public function getSpotifyArtists(Request $request)
    {
        //открываем файл подписок
        $artists = System::getUserLibraryJson("artists", $request);

        //если он есть
        if($artists != false)
        {   
            //записываем случайные 5 подписок
            $randomFive = [];
        
            //использованные индексы элементов массива с подписками
            $usedNumbers = [];
    
            //пока не наберется 5 исполнителей
            while(count($randomFive) <= 4)
            {   
                //генерим рандомное число, это будет индекс исполнителя в массиве с ними
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
    
            //записываем кол-во подписок и случайные пять
            $response = ['count' => count($artists), 'random_five' => $randomFive];
            
            return response()->json($response);
        }

    }

    //getUserLibraryTime
    //посчитать общее время всех треков
    //возвращает JSON с подсчитанным общим времен всех треков в минутах, часах, днях и месяцах
    //а так же случайное изображение обложки альбома (из библиотеки пользователя) для фона
    //параметры: реквест
    public function getUserLibraryTime(Request $request)
    {
        //получаем файл треков
        $tracks = System::getUserLibraryJson("tracks", $request);

        //если он есть
        if($tracks != false)
        {    
            //считаем общее кол-во минут всех треков
            //длина треков в Spotify API записана в миллисекундах, поэтому делим их на 60000
            //чтобы получить минуты
            $overallMinutes = 0;

            foreach($tracks as $track)
            { $overallMinutes += round($track->duration_ms / 60000); }

            $overallHours = floor($overallMinutes / 60); //общее кол-во часов (кол-во минут / 60 минут)
            $overallDays = floor($overallHours / 24); //общее кол-во дней (кол-во часов / 24 часа)
            $overallMonths = floor($overallDays / 30); //общее кол-во месяцев (кол-во дней / 30 дней)

            //вычисляем какое слово нужно подставить в конец (1 минуТА, 2 минуТЫ и т.п)
            $overallMinutes .= Helpers::pickTheWord($overallMinutes, "минут", "минута", "минуты");
            
            //анадлогично для остальных измерений, если они больше нуля
            if($overallHours > 0)
            {  $overallHours .= Helpers::pickTheWord($overallHours, "часов", "час", "часа"); }
            
            if($overallDays > 0)
            {  $overallDays .= Helpers::pickTheWord($overallDays, "дней", "день", "дня"); }
            
            if($overallMonths > 0)
            {  $overallMonths .= Helpers::pickTheWord($overallMonths, "месяцев", "месяц", "месяца"); }

            //получить случайное изображение с обложкой альбома для фоновой картинки
            //получаем случайный трек из файла
            $randNum = rand(0, count($tracks) - 1);
            $randomTrackId = $tracks[$randNum]->id;

            //проверяем токен
            $checkToken = System::checkSpotifyAccessToken($request);
            //если токен действительный
            if($checkToken != false)
            {
                //делаем запрос к Spotify API и получаем обложку трека
                $api = config('spotify_api');
                $coverImageUrl = $api->getTrack($randomTrackId)->album->images[0]->url;
            }   


            $response = ['overallMinutes' => $overallMinutes, 'overallHours' => $overallHours,
                        'overallDays' => $overallDays, 'overallMonths' => $overallMonths, 
                        'coverImageUrl' => $coverImageUrl];

            return response()->json($response);
        }
        else
        { return response()->json(false); }
    }

    //getFiveLongestAndShortestTracks
    //получить пять самых длинных и коротких треков из библиотеки
    //возвращает JSON с треками
    //параметры: реквест
    public function getFiveLongestAndShortestTracks(Request $request)
    {
        //получаем все треки
        $tracks = System::getUserLibraryJson("tracks", $request);

        if($tracks != false)
        {  
            //получаем полный список треков с id трека, длиной, обложкой, названием и url
            $tracksClean = [];

            foreach($tracks as $track)
            {
                $id = $track->id;
                $duration = Helpers::trackDuration($track->duration_ms);
                $cover = $track->album->images[count($track->album->images) - 1]->url;
                $name = Helpers::getFullNameOfItem($track);
                $url = $track->external_urls->spotify;
                array_push($tracksClean, ['id' => $id, 'duration' => $duration, 'cover' => $cover, 'name' => $name, 'url' => $url]);
            }
            
            //сортируем треки по убыванию и возрастанию по ключу 'duration'
            $tracksDesc = Helpers::sortArrayByKey($tracksClean, 'duration', 'desc');
            $tracksAsc = Helpers::sortArrayByKey($tracksClean, 'duration', 'asc');

            //берем верхние пять элементов у обоих массивов
            //таким образом получаем пять самых длинных и коротких треков
            $topFiveDesc = array_slice($tracksDesc, 0, 5, true);
            $topFiveAsc = array_slice($tracksAsc, 0, 5, true);

            $response = ['fiveLongest' => $topFiveDesc, 'fiveShortest' => $topFiveAsc];

            return response()->json($response);
        }
    }

    //getAverageLengthOfTrack
    //вычислить среднюю длину трека в библиотеке
    //возвращает строку со средней длиной трека
    //параметры: реквест
    public function getAverageLengthOfTrack(Request $request)
    {
        //получить файл треков
        $tracks = System::getUserLibraryJson("tracks", $request);

        //если он есть
        if($tracks != null)
        {   
            $durationMs = []; //записываем массив с длиной треков в миллисекундах
            
            foreach($tracks as $track)
            { array_push($durationMs, $track->duration_ms); }
    
            $durationMn = [];   //получаем массив с длиной треков в минутах (округленной вниз)
    
            foreach($durationMs as $item)
            { array_push($durationMn, intval(floor($item / 60000))); }
            
            $countDurations = array_count_values($durationMn);  //считаем моду
            $mode = array_search(max($countDurations), $countDurations);
            
            $response = $mode . Helpers::pickTheWord($mode, "минут", "минута", "минуты");

            return response()->json($response);
        }
        else
        { return response()->json(false); }

    }

    // НЕ ИСПОЛЬЗУЕТСЯ
    //сгенерировать изображение для фона профиля (ВЫРЕЗАТЬ И ИСПОЛЬЗОВАТЬ В ДРУГОМ МЕСТЕ)
    public function generateBackgroundImage(Request $request)
    {
        $tracks = System::getUserLibraryJson("tracks", $request);

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

    //getFavoriteGenres
    //получить любимые жанры пользователя в виде графика
    //возвращает JSON с десятью самыми популярными жанрами у пользователя за последний месяц
    //параметры: реквест
    public function getFavoriteGenres(Request $request)
    {   
        //проверяем токен
        $checkToken = System::checkSpotifyAccessToken($request);

        if($checkToken != false)
        {
            //получаем api
            $api = config('spotify_api');

            //массив для подсчета жанров
            $genresCount = [];
            //смещение для получения треков
            $offset = 0;
            //пока смещение меньше 50, т.е мы получим только 98 последних треков (больше не позволяет Spotify API)
            while($offset < 50)
            {
                //получаем треки
                $tracks = $api->getMyTop('tracks', ['limit' => 49, 'time_range' => 'short_term', 'offset' => $offset])->items;

                //если треки есть
                if($tracks != null)
                {   
                    $artistsArray = []; //массив для исполнителей
                    $genresArray = []; //массив для жанров
                    
                    //получаем id всех имеющихся исполнителей через треки
                    foreach($tracks as $track)
                    {
                        foreach($track->artists as $artist)
                        {
                            if(array_search($artist->id, $artistsArray) == false)
                            { array_push($artistsArray, $artist->id); }
                        }   
                    }

                    //получаем список жанров через исполнителей
                    //читаем массив с исполнителями и записываем жанры
                    for($i = 0; $i < count($artistsArray); $i++)
                    {
                        $genres = $api->getArtist($artistsArray[$i]);

                        foreach($genres->genres as $genre)
                        { array_push($genresArray, $genre); }
                    }
                    
                    //подсчитываем сколько раз встречается каждый из жанров
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

            //сортируем массив с подсчитанными жанрами по убыванию
            arsort($genresCount);
            //получаем верхние десять
            $topTenGenres = array_slice($genresCount, 0, 10, true);

            return response()->json($topTenGenres);
        }
        else
        { return response()->json(false); }

    }

    //getUniqueArtists
    //посчитать кол-во исполнителей в библиотеке
    //возвращает строку с кол-вом исполнителей
    //параметры: реквест
    public function getUniqueArtists(Request $request)
    {
        //получаем файл треков
        $tracks = System::getUserLibraryJson("tracks", $request);

        //если он есть
        if($tracks != null)
        {
            $artistsArray = []; //массив для исполнителей

            //записываем id всех исполнителей в массив
            foreach($tracks as $track)
            {
                foreach($track->artists as $artist)
                {
                    if(array_search($artist->id, $artistsArray) == false)
                    { array_push($artistsArray, $artist->id); }
                }   
            }

            //cчитаем уникальных
            $count = count(array_unique($artistsArray));

            //подставляем подходящее слово
            $countArtists = $count . Helpers::pickTheWord($count, "различных исполнителей", "исполнителя", "разных исполнителей");
            
            //получаем случайное фото исполнителя для фоновой картинки
            $artistImageUrl = ""; //пустая строка для url картинки

            //проверяем токен
            $checkToken = System::checkSpotifyAccessToken($request);

            if($checkToken != false)
            {   
                //случайный id исполнителя из массива
                $randArtistId = $artistsArray[rand(0, $count - 1)];

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

    //getYearsAndDecades
    //посчитать любимые года и десятилетия
    //возращает JSON с годами и десятилетиями для графика
    //параметры: реквест
    public function getYearsAndDecades(Request $request)
    {
        //открываем файл с треками
        $tracks = System::getUserLibraryJson("tracks", $request);

        //если он есть
        if($tracks != null)
        {       
            //массив для всех годов
            $allYears = [];

            //запись всех годов в массив
            foreach($tracks as $track)
            {
                //получить год из даты выхода трека
                $year = Helpers::getItemReleaseDate($track, "track", "short");

                array_push($allYears, $year); 
            }

            //подсчет кол-ва всех годов
            $countYears = [];

            //считаем сколько раз встречается каждый год
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

            //подсчет кол-ва всех десятилетий
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
                array_push($decadeColors, Helpers::randomHslColor(['offset' => $offset]));
                $offset += 60;
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

            $maxDecadeSongs = $countDecades[key($countDecades)] . Helpers::pickTheWord($countDecades[key($countDecades)], "песен", "песня", "песни");
            $maxYearSongs = $countYears[key($countYears)] . Helpers::pickTheWord($countYears[key($countYears)], "песен", "песня", "песни");

            $response['maxDecadeSongs'] = $maxDecadeSongs;
            $response['maxYearSongs'] = $maxYearSongs;

            return response()->json($response);
        }   
        else
        { return response()->json(false); }
    }

    // ТОП 10

    //getTop10Tracks
    //получить топ 10 треков за все время или за месяц
    //возвращает JSON с 10-тью самыми прослушиваемыми треками за все время
    //параметры: реквест, тип запроса: топ за все время или за месяц (alltime и month)
    public function getTop10Tracks(Request $request, $top10Type)
    {
        //проверка токена
        $checkToken = System::checkSpotifyAccessToken($request);

        //если токен действительный
        if($checkToken != false)
        {
            //получаем api
            $api = config('spotify_api');
            
            $options = ['limit' => 10];

            //если тип запроса alltime, то в опциях устанавливаем time_range = long_term
            if($top10Type == "alltime")
            {
                $options['time_range'] = 'long_term';
            }
            //если month, то тогда short_term
            else if($top10Type == "month")
            {
                $options['time_range'] = 'short_term';
            }
            else
            { return response()->json(false); }

            //получаем треки и запсиываем необходимую информацию о них
            $top10Tracks = $api->getMyTop('tracks', $options);
            $count = 1;
            $tracks = [];
            foreach($top10Tracks->items as $track)
            {
                $trackInfo = [];
                $trackInfo['count'] = $count;
                $trackInfo['id'] = $track->id;
                $trackInfo['track_name'] = Helpers::getFullNameOfItem($track);
                $trackInfo['cover'] = $track->album->images[count($track->album->images)-1]->url;
                $trackInfo['url'] = $track->external_urls->spotify;
                $trackInfo['album'] = $track->album->name;
                $trackInfo['album_url'] = $track->album->external_urls->spotify;
                $trackInfo['album_year'] = Helpers::getItemReleaseDate($track, "track", "short");

                array_push($tracks, $trackInfo);
                $count++;
            }

            //случайная обложка
            $randTrackId = $tracks[rand(0, count($tracks) - 1)]['id'];

            $albumCover = $api->getTrack($randTrackId)->album->images[0]->url;

            $response = [];
            $response['tracks'] = $tracks;
            $response['backgroundImage'] = $albumCover;
 
            return response()->json($response);
        }   
        else
        { return response()->json(false); }
    }

    //getTop10Artists
    //получить топ 10 исполнителей
    //возвращает JSON с топ 10 исполнителей
    //параметры: реквест и тип запроса: alltime или month
    public function getTop10Artists(Request $request, $top10Type)
    {
        //проверка токена
        $checkToken = System::checkSpotifyAccessToken($request);

        if($checkToken != false)
        {
            //получаем api
            $api = config('spotify_api');
        
            $options = ['limit' => 10];

            //если тип запроса alltime, то в опциях устанавливаем time_range = long_term
            if($top10Type == "alltime")
            {
                $options['time_range'] = 'long_term';
            }
            //если month, то тогда short_term
            else if($top10Type == "month")
            {
                $options['time_range'] = 'short_term';
            }
            else
            { return response()->json(false); }

            //получаем треки и запсиываем необходимую информацию о них
            $top10Artists = $api->getMyTop('artists', $options);

            $count = 1;
            $artists = [];
            foreach($top10Artists->items as $artist)
            {
                $artistInfo = [];
                $artistInfo['count'] = $count;
                $artistInfo['id'] = $artist->id;
                $artistInfo['artist_name'] = $artist->name;
                $artistInfo['photo'] = $artist->images[count($artist->images)-1]->url;
                $artistInfo['url'] = $artist->external_urls->spotify;

                array_push($artists, $artistInfo);
                $count++;
            }

            //случайное фото исполнителя
            $randArtistId = $artists[rand(0, count($artists) - 1)]['id'];

            $artistPhoto = $api->getArtist($randArtistId)->images[0]->url;

            $response['artists'] = $artists;
            $response['backgroundImage'] = $artistPhoto;

            return response()->json($response);
        }
        else
        { return response()->json(false); }
    }

    //getTop10TracksByLength
    //получить топ 10 самых длинных или коротких треков
    //возвращает JSON с топ 10 треков
    //параметры: реквест, тип запроса: long или short
    public function getTop10TracksByLength(Request $request, $top10Type)
    {
          //открываем файл с треками
          $tracks = System::getUserLibraryJson("tracks", $request);

          //если он есть
          if($tracks != null)
          {  
            //получаем полный список треков с id трека, длиной, обложкой, названием и url

            $tracksClean = [];

            foreach($tracks as $track)
            {   
                $trackInfo = [];
                $trackInfo['id'] = $track->id;
                $trackInfo['duration'] = Helpers::trackDuration($track->duration_ms);
                $trackInfo['cover'] = $track->album->images[count($track->album->images) - 1]->url;
                $trackInfo['track_name'] = Helpers::getFullNameOfItem($track);
                $trackInfo['url'] = $track->external_urls->spotify;
                $trackInfo['album'] = $track->album->name;
                $trackInfo['album_url'] = $track->album->external_urls->spotify;
                $trackInfo['album_year'] = Helpers::getItemReleaseDate($track, "track", "short");
                array_push($tracksClean, $trackInfo);
            }

            //сортируем треки по убыванию и возрастанию по ключу 'duration'
            $tracksSorted = "";

            if($top10Type == "long")
            {
                $tracksSorted = Helpers::sortArrayByKey( $tracksClean, 'duration', 'desc');           
            }
            else if ($top10Type == "short")
            {
                $tracksSorted = Helpers::sortArrayByKey( $tracksClean, 'duration', 'asc');
            }

            //берем верхние десять элементов
            $topTen = array_slice($tracksSorted, 0, 10, true);

            for($i = 1; $i <= count($topTen); $i++)
            {
                $topTen[$i-1]['count'] = $i;
            }

            $response = [];
            $response['tracks'] = $topTen;
     
            //проверка токена
            $checkToken = System::checkSpotifyAccessToken($request);

            if($checkToken != false)
            {
                $api = config('spotify_api');

                //случайная обложка трека
                $randTrackId = $topTen[rand(0, count($topTen) - 1)]['id'];
                
                $albumCover = $api->getTrack($randTrackId )->album->images[0]->url;

                $response['backgroundImage'] = $albumCover;

                return response()->json($response);
            }
            else
            { return response()->json(false); }

          }
          else
          { return response()->json(false); }
    }

    //getTop10ArtistsByTracks
    //получить топ 10 исполнителей по кол-ву треков
    //возвращает JSON с топ 10 исполнителей пол кол-ву треков
    //параметры: реквест
    public function getTop10ArtistsByTracks(Request $request)
    {
        //открываем файл с треками
        $tracks = System::getUserLibraryJson("tracks", $request);

        //если он есть
        if($tracks != null)
        {  
            $artists = [];

            //получаем все id исполнителей из списка треков
            foreach($tracks as $track)
            {
                foreach($track->artists as $artist)
                { array_push($artists, $artist->id); }
            }

            $artistsCount = [];

            //считаем сколько раз встречается каждый id
            foreach($artists as $artist)
            {
                if(array_key_exists($artist, $artistsCount) === false)
                { $artistsCount[$artist] = 1; }
                else
                { $artistsCount[$artist] += 1; }
            }

            //сортировка по убыванию
            arsort($artistsCount);

            //проверяем токен
            $checkToken = System::checkSpotifyAccessToken($request);

            //список всех id исполнителей
            $artistIds = array_keys($artistsCount);

            //если он действительный
            if($checkToken != false)
            {   
                //получаем информацию об исполнителе из api по id и записываем в список
                $api = config('spotify_api');

                $artists = [];
    
                for($i = 0; $i <= 9; $i++)
                {
                    $artistInfo = [];
                    $artist = $api->getArtist($artistIds[$i]);
                    $artistInfo['count'] = $i+1;
                    $artistInfo['id'] = $artist->id;
                    $artistInfo['artist_name'] = $artist->name;
                    $artistInfo['photo'] = $artist->images[count($artist->images)-1]->url;
                    $artistInfo['url'] = $artist->external_urls->spotify;
                    $artistInfo['track_count'] = $artistsCount[$artistIds[$i]] . Helpers::pickTheWord($artistsCount[$artistIds[$i]], "треков", "трек", "трека");
    
                    array_push($artists, $artistInfo);
                }

                $response['artists'] = $artists;

                //получаем случайное фото исполнителя из топ 10
                $randomArtistId = $artistIds[rand(0, 9)];

                $response['backgroundImage'] = $api->getArtist($randomArtistId)->images[0]->url;

                return response()->json($response);
            }
        }
        else
        { return response()->json(false); }
    }

    
    //getTop10ArtistsByTime
    //получить топ 10 исполнителей по кол-ву времени треков
    //возвращает JSON с топ 10 исполнителей пол кол-ву треков
    //параметры: реквест
    public function getTop10ArtistsByTime(Request $request)
    {
        //открываем файл с треками
        $tracks = System::getUserLibraryJson("tracks", $request);

        //если он есть
        if($tracks != null)
        {  
            $artists = [];

            //получаем все id исполнителей из списка треков
            foreach($tracks as $track)
            {
                foreach($track->artists as $artist)
                { array_push($artists, ['id' => $artist->id, 'duration_ms' => $track->duration_ms]); }
            }

            $artistsCount = [];

            //считаем время у каждого id
            foreach($artists as $artist)
            {
                if(array_key_exists($artist['id'], $artistsCount) === false)
                { $artistsCount[$artist['id']] = $artist['duration_ms']; }
                else
                { $artistsCount[$artist['id']] += $artist['duration_ms']; }
            }

            //сортировка по убыванию
            arsort($artistsCount);

            //проверяем токен
            $checkToken = System::checkSpotifyAccessToken($request);

            //список всех id исполнителей
            $artistIds = array_keys($artistsCount);

            //если он действительный
            if($checkToken != false)
            {   
                //получаем информацию об исполнителе из api по id и записываем в список
                $api = config('spotify_api');

                $artists = [];
    
                for($i = 0; $i <= 9; $i++)
                {
                    $artistInfo = [];
                    $artist = $api->getArtist($artistIds[$i]);
                    $artistInfo['count'] = $i+1;
                    $artistInfo['id'] = $artist->id;
                    $artistInfo['artist_name'] = $artist->name;
                    $artistInfo['photo'] = $artist->images[count($artist->images)-1]->url;
                    $artistInfo['url'] = $artist->external_urls->spotify;
                    $artistInfo['track_count'] = Helpers::trackDuration($artistsCount[$artistIds[$i]]);
    
                    array_push($artists, $artistInfo);
                }

                $response['artists'] = $artists;

                //получаем случайное фото исполнителя из топ 10
                $randomArtistId = $artistIds[rand(0, 9)];

                $response['backgroundImage'] = $api->getArtist($randomArtistId)->images[0]->url;

                return response()->json($response);
            }
        }
        else
        { return response()->json(false); }
    }

}
