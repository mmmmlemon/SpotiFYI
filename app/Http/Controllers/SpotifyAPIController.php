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
           $spotifyUserTracksCount += count($spotifyUserTracks->items);
           //пока смещение меньше 100, прибавляем кол-во полученные треков 
           //и получаем следующие 50 с новым смещением
           //итого максимум будет 150 треков, в зависимости от кол-ва треков
           //будет выводиться разное сообщение на главной странице
           while($offset <= 100 && $spotifyUserTracks->next != null)
           {
                $spotifyUserTracksCount += count($spotifyUserTracks->items);
                $offset += 50;
                $result = $api->getMySavedTracks(['limit' => 50, 'offset' => $offset]);
                if(count($result->items) >= 20){
                    $spotifyUserTracks = $result;
                }
           }

           $tracks = [];
           if($spotifyUserTracksCount >= 50)
           {    
                //убираем дупликаты обложек (в залайканых песнях обложки могут повторяться)
                $spotifyCovers = [];
                foreach($spotifyUserTracks->items as $track){
                        array_push($spotifyCovers, $track->track->album->images[0]->url);
                }


                $spotifyCoversUnique = array_values(array_unique($spotifyCovers));
        
                $len = count($spotifyCoversUnique);
                $randomNumbers =  Helpers::randomNumbers(0, $len-1, 5);
                for($i = 0; $i <= 4; $i++){
                        array_push($tracks, $spotifyCoversUnique[$randomNumbers[$i]]);
                }
           }

           return response()->json(['trackCount'=>$spotifyUserTracksCount,'trackCovers'=> $tracks]);
        }
        else
        { return response()->json(false); }
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

            $avatarUrl = "";
            
            //если у пользователя есть установленный аватар, то берем его
            if(count($profile->images) > 0)
            { $avatarUrl = $profile->images[0]->url; }
            //если нет, то берем заглушку из настроек
            else
            { $avatarUrl = asset(config('settings')->user_img); }

            $response = ['spotifyUsername' => $profile->display_name, 
                         'country' => "https://www.countryflags.io/" . $profile->country . "/flat/32.png", 
                         'profile_url' => $profile->external_urls->spotify, 'followers' => $profile->followers->total,
                         'avatar' => $avatarUrl, 
                         'subscription' => $profile->product];
           
            return response()->json($response);
        }
        else
        { return response()->json(false); }
    }

    //getSpotifyUserLibrary
    //получить библиотеку пользователя целиком (треки, альбомы и подписки)
    //информация о библиотеке записывается в JSON файлы которые хранятся в storage
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

            $options['offset'] = 0;

            //получаем все альбомы
            while(count($spotifyMyAlbums) > 0)
            {
                foreach($spotifyMyAlbums as $item)
                { array_push($spotifyUserAlbums, $item->album); }

                $options['offset'] += 50;
                $spotifyMyAlbums = $api->getMySavedAlbums($options)->items;
            }

            //считаем количество треков
            $countTracks = count($spotifyUserTracks);

            //если треков меньше 50-ти, возвращаем ошибку
            if($countTracks < 50)
            { 
                return response()->json(['result'=> 'libraryError', 
                                         'errorMsg' => 'Слишком мало треков в библиотеке (треков: '.$countTracks.", нужно: 50). Добавь еще!"]);
            }
            //если треков 50 или больше
            else
            { 
                //получаем все альбомы
                while(count($spotifyMyAlbums) > 0)
                {
                    foreach($spotifyMyAlbums as $item)
                    { array_push($spotifyUserAlbums, $item->album); }

                    $options['offset'] += 50;
                    $spotifyMyAlbums = $api->getMySavedAlbums($options)->items;
                }

                //получаем все подписки на аритистов
                while(count($spotifyMyArtists) > 0)
                {
                    foreach($spotifyMyArtists as $item)
                    { array_push($spotifyUserArtists, $item); }

                    $options['after'] = $item->id;
                    $spotifyMyArtists = $api->getUserFollowedArtists($options)->artists->items;
                }

                //сохраняем библиотку в JSON файлы
                //проверяем что папка storage/../user_libraries существует
                $check = File::exists(storage_path("app/public/user_libraries"));

                //если папки нет, то создаем её
                if($check != true)
                { Storage::disk('public')->makeDirectory("user_libraries"); }
                
                //получаем из Cookies рандомное имя папки в которую будут сохраняться JSON'ы
                $folderName = $request->cookie('rand_name');

                //создаем эту папку, и если она создалась то записываем содержимое массивов в файлы
                if(Storage::disk('public')->makeDirectory("user_libraries/" . $folderName))
                {   
                    //сохраняем треки
                    File::put(storage_path("app/public/user_libraries/" . $folderName . "/" . "tracks.json"), json_encode($spotifyUserTracks));
                    //сохраняем альбомы
                    File::put(storage_path("app/public/user_libraries/" . $folderName . "/" . "albums.json"), json_encode($spotifyUserAlbums));
                    //сохраняем подписки
                    File::put(storage_path("app/public/user_libraries/" . $folderName . "/" . "artists.json"), json_encode($spotifyUserArtists));
                    
                    //если все получилось, то возвращаем true
                    return response()->json(['result' => true]);
                }
                else { return response()->json(false); }    
            } 
        }
        else
        { return response()->json(['result' => false]); }
    }

    // ОБЩЕЕ

    //getSpotifyTracks
    //посчитать количество треков в библиотеке пользователя и вывести последние пять
    //возвращает JSON с кол-вом треков и последние 5 треков в библиотеке пользователя
    //параметры: реквест
    public function getSpotifyTracks(Request $request)
    {   
        //открываем файл треков из storage/../user_libraries
        $tracks = System::getUserLibraryJson("tracks", $request);

        //если файл есть
        if($tracks != false)
        {
            //записываем в массив последние пять треков
            $lastFive = [];

            for($i = 0; $i < 5; $i++)
            {   
                //получаем полное название трека
                $name = Helpers::getFullNameOfItem($tracks[$i], "fullname");

                //записываем в массив нужную информацию о треке
                array_push($lastFive, ['id' => $tracks[$i]->id,
                                       'cover' => $tracks[$i]->album->images[count($tracks[$i]->album->images) - 1]->url,
                                       'name' => $name,
                                       'url' => $tracks[$i]->external_urls->spotify]);
            }
    
            //записываем в массив кол-во треков и последние 5 треков
            $response = ['count' => count($tracks), 'lastFive' => $lastFive];

            return response()->json($response);
        }
        else
        { return response()->json(false); }
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

            //кол-во альбомов которое нужно вывести
            $count = 5;

            //если альбомов в библиотеке меньше пяти, то приравнивем count к кол-ву альбомов
            if(count($albums) < 5)
            { $count = count($albums); }
            
            for($i = 0; $i < $count; $i++)
            {
                //получаем полное название альбома
                $name = Helpers::getFullNameOfItem($albums[$i], "fullname");
                array_push($lastFive, ['id' => $albums[$i]->id,
                                       'cover' => $albums[$i]->images[count($albums[$i]->images) - 1]->url,
                                       'name' => $name,
                                       'url' => $albums[$i]->external_urls->spotify]);
            }
            
            //записыаем кол-во альбомов и последние 5 альбомов
            $response = ['count' => count($albums), 'lastFive' => $lastFive];
            
            return response()->json($response);
        }
        else
        { 
            $response = ['count' => 0, 'lastFive' => false];
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
            
            //кол-во артистов которых нужно показать
            $count = 5;

            //если подписок меньше пяти, то приравниваем count к кол-ву подписок
            if(count($artists) < 5)
            { $count = count($artists); }

            //пока не наберется 5 исполнителей
            while(count($randomFive) < $count)
            {   
                //генерим рандомное число, это будет индекс исполнителя в массиве с ними
                $randomNumber = rand(0,count($artists) - 1);
                //если индекс еще не был использован
                if(array_search($randomNumber, $usedNumbers) === false)
                {   
                    //добавляем индекс в массив и добавляем исполнителя
                    array_push($usedNumbers, $randomNumber);
                    array_push($randomFive, ['id' => $artists[$randomNumber]->id,
                                             'cover' => $artists[$randomNumber]->images[count($artists[$randomNumber]->images)-1]->url,
                                             'name' => $artists[$randomNumber]->name,
                                             'url' => $artists[$randomNumber]->external_urls->spotify]);
                }
            }   
    
            //записываем кол-во подписок и случайные пять
            $response = ['count' => count($artists), 'lastFive' => $randomFive];
            
            return response()->json($response);
        }
        else
        {    
            $response = ['count' => 0, 'lastFive' => false];
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
            $overallMinutesWord = " " . Helpers::pickTheWord($overallMinutes, "минут", "минута", "минуты");
            
            //аналогично для остальных измерений, если они больше нуля
            $overallHoursWord = null;
            $overallDaysWord = null;
            $overallMonthsWord = null;

            if($overallHours > 0)
            {  $overallHoursWord .= " " . Helpers::pickTheWord($overallHours, "часов", "час", "часа"); }
            
            if($overallDays > 0)
            {  $overallDaysWord .= " " . Helpers::pickTheWord($overallDays, "дней", "день", "дня"); }
            
            if($overallMonths > 0)
            {  $overallMonthsWord .= " " . Helpers::pickTheWord($overallMonths, "месяцев", "месяц", "месяца"); }

            //получить случайное изображение с обложкой альбома для фоновой картинки
            //получаем случайный трек из файла
            $randNum = rand(0, count($tracks) - 1);
            $randomTrackId = $tracks[$randNum]->id;

            //проверяем токен
            // $checkToken = System::checkSpotifyAccessToken($request);
            // //если токен действительный
            // if($checkToken != false)
            // {
            //     //делаем запрос к Spotify API и получаем обложку трека
            //     $api = config('spotify_api');
            //     $coverImageUrl = $api->getTrack($randomTrackId)->album->images[0]->url;
            // }   

            $response = ['overallMinutes' => $overallMinutes, 
                         'overallHours' => $overallHours,
                         'overallDays' => $overallDays, 
                         'overallMonths' => $overallMonths,
                         'overallMinutesWord' => $overallMinutesWord,
                         'overallHoursWord' => $overallHoursWord,
                         'overallDaysWord' => $overallDaysWord,
                         'overallMonthsWord' => '$overallMonthsWord',
                        //  'coverImageUrl' => $coverImageUrl
                        ];

            return response()->json($response);
        }
        else
        { return response()->json(false); }
    }

    //getFiveLongestAndShortestTracks
    //получить пять самых длинных и коротких треков из библиотеки
    //возвращает JSON с треками
    //параметры: реквест
    //ВЫРЕЗАНО
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
                $trackInfo = [];
                $trackInfo['id'] = $track->id;
                $trackInfo['duration'] = Helpers::getTrackDuration($track->duration_ms);
                $trackInfo['cover'] = $track->album->images[count($track->album->images) - 1]->url;
                $trackInfo['name'] = Helpers::getFullNameOfItem($track, "fullname");
                $trackInfo['url'] = $track->external_urls->spotify;
                array_push($tracksClean, $trackInfo);
            }
            
            //сортируем треки по убыванию и возрастанию по ключу 'duration'
            $tracksDesc = Helpers::sortArrayByKey($tracksClean, 'duration', 'desc');
            $tracksAsc = Helpers::sortArrayByKey($tracksClean, 'duration', 'asc');

            //берем верхние пять элементов у обоих массивов
            //таким образом получаем пять самых длинных и коротких треков
            $topFiveDesc = array_slice($tracksDesc, 0, 5, true);
            $topFiveAsc = array_slice($tracksAsc, 0, 5, true);

            //порядковые номера для вывода
            $count = 1;

            for($i = 0; $i < 5; $i++)
            {
                $topFiveDesc[$i]['count'] = $count;
                $topFiveAsc[$i]['count'] = $count;
                $count++;
            }

            $response = ['fiveLongest' => $topFiveDesc, 'fiveShortest' => $topFiveAsc];

            return response()->json($response);
        }
        else
        { return response()->json(false); }
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
        if($tracks != false)
        {   
            $durationMinutes = []; //записываем массив с длиной треков в минутах

            //длина в милисекундах / 60000 округленная вниз
            foreach($tracks as $track)
            { array_push($durationMinutes, intval(floor($track->duration_ms / 60000))); }
               
            $countDurations = array_count_values($durationMinutes); //считаем моду
   
            $mode = array_search(max($countDurations), $countDurations);
            
            $response = $mode . " " . Helpers::pickTheWord($mode, "минут", "минута", "минуты");

            return response()->json($response);
        }
        else
        { return response()->json(false); }
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

            //смещение для получения треков
            $offset = 0;

            $artistsArray = []; //массив для исполнителей 
            $genresCount = []; //массив для подсчета жанров

            //пока смещение меньше 49, будем добавлять в массив треки 
            //(можно получить только 98 последних треков)
            //(больше не позволяет Spotify API, хз почему именно 98)
            while($offset <= 49)
            {
                //получаем треки
                $tracks = $api->getMyTop('tracks', ['limit' => 49, 'time_range' => 'short_term', 'offset' => $offset])->items;

                //если треки есть
                if($tracks != false)
                {   
                    $genresArray = []; //массив для жанров
                    
                    //получаем id всех имеющихся исполнителей через треки
                    foreach($tracks as $track)
                    {
                        foreach($track->artists as $artist)
                        {
                            if(array_search($artist->id, $artistsArray) === false)
                            { array_push($artistsArray, $artist->id); }
                        }   
                    }
                    //добавляем +49 к смещению
                    $offset += 49;

                }
                else
                { return response()->json('noTracks'); }
            }

            //получаем список жанров через исполнителей
            //читаем массив с исполнителями и записываем жанры
            for($i = 0; $i <= count($artistsArray) - 1; $i++)
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
        if($tracks != false)
        {
            $artistsArray = []; //массив для исполнителей

            //записываем id всех уникальных исполнителей в массив
            foreach($tracks as $track)
            {
                foreach($track->artists as $artist)
                {
                    if(array_search($artist->id, $artistsArray) === false)
                    { array_push($artistsArray, $artist->id); }
                }   
            }

            //cчитаем уникальных
            $count = count($artistsArray);

            //подставляем подходящее слово
            $countArtists = $count . " " . Helpers::pickTheWord($count, "различных исполнителей", "исполнителя", "разных исполнителей");
            
            // //получаем случайное фото исполнителя для фоновой картинки
            // $artistImageUrl = ""; //пустая строка для url картинки

            // //проверяем токен
            // $checkToken = System::checkSpotifyAccessToken($request);

            // if($checkToken != false)
            // {   
            //     //случайный id исполнителя из массива
            //     $randArtistId = $artistsArray[rand(0, $count - 1)];

            //     //получаем api
            //     $api = config('spotify_api');
            //     $artistImageUrl = $api->getArtist($randArtistId)->images[0]->url;
            // }

            $response = ['countArtists' => $countArtists, 
                            // 'artistImageUrl' => $artistImageUrl
                        ];

            return response()->json($response);
        }
        else
        { return response()->json(false); }
    }

    //getYearsAndDecades
    //посчитать любимые года и десятилетия по всем трекам или по трекам за последний месяц
    //возращает JSON с годами и десятилетиями для графика
    //параметры: реквест
    public function getYearsAndDecades(Request $request)
    {
        //массив в который будут записаны треки
        $tracks = [];
    
        //открываем файл с треками
        $tracks = System::getUserLibraryJson("tracks", $request);

        //получение треков прослушанных за последний месяц
        // else if($type == "month") 
        // {
        //     //проверяем токен
        //     $checkToken = System::checkSpotifyAccessToken($request);

        //     if($checkToken != false)
        //     {
        //         //получаем api
        //         $api = config('spotify_api');

        //         //смещение для получения треков
        //         $offset = 0;
                
        //         //пока смещение меньше 49, будем добавлять в массив треки 
        //         //(можно получить только 98 последних треков)
        //         //(больше не позволяет Spotify API, хз почему именно 98)
        //         while($offset < 49)
        //         {
        //             //получаем треки
        //             $tracksApi = $api->getMyTop('tracks', ['limit' => 49, 'time_range' => 'short_term', 'offset' => $offset])->items;

        //             //если треки есть
        //             if($tracksApi != null)
        //             {   
        //                 foreach($tracksApi as $track)
        //                 { array_push($tracks, $track); }
        //             }

        //             $offset += 49;  
        //         }

        //         //если треков меньше десяти, то возвращаем false
        //         if(count($tracks) < 10)
        //         { return response()->json(false); }
        //     }
        //     else
        //     { return response()->json(false); }
        // }
        // else
        // { return response()->json(false); }
 
        //если он есть
        if($tracks != false && $tracks != null)
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

            //считаем сколько раз встречается каждый год
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
            arsort($countDecades);

            $response = ['countYears' => $countYears, 'countDecades' => $countDecades];

            //генерируем цвета для графиков
            //цвета для графика десятилетий
            $decadeColors = [];
            $offset = 0;
            $min = 330;
            $max = 360;
            for($i = 0; $i < 10; $i++)
            {   
                array_push($decadeColors, Helpers::randomHslColor($min, $max));
                $offset += 60;

                $min -= 30;
                $max -= 30;
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

            $maxDecadeSongs = $countDecades[key($countDecades)] . " " . Helpers::pickTheWord($countDecades[key($countDecades)], "песен", "песня", "песни");
            $maxYearSongs = $countYears[key($countYears)] . " " . Helpers::pickTheWord($countYears[key($countYears)], "песен", "песня", "песни");

            $response['maxDecadeSongs'] = $maxDecadeSongs;
            $response['maxYearSongs'] = $maxYearSongs;

            //процент песен эпохи от общего кол-ва
            $allTracks = count($tracks);

            $percent = intval(floor($countDecades[key($countDecades)] / ($allTracks / 100)));

            //ищем топ трек топ года
            $sortedTopTracks = [];
            foreach($tracks as $track){
                $year= intval(Helpers::getItemReleaseDate($track, "track","short"));    
                    if($year === $maxYear){
                    array_push($sortedTopTracks, ['cover' => $track->album->images[0]->url,
                                                    'trackName' => Helpers::getFullNameOfItem($track),
                                                    'url' => $track->external_urls->spotify]);
                }
            }

            $songOfYear = $sortedTopTracks[rand(0, count($sortedTopTracks) - 1)];

            //картинки
            //5 случайных обложек из десятилетия
            //получаем все обложки треков из десятилетия
            $sortedTrackCovers = [];
            foreach($tracks as $track){

                $year= intval(Helpers::getItemReleaseDate($track, "track","short"));
                
                $decade = intval(substr($year, 0, 3) . "0");

                if($decade === $maxDecade){
                    array_push($sortedTrackCovers , $track->album->images[0]->url);
                }
            }

            $sortedTrackCovers = array_values(array_unique($sortedTrackCovers));

            //удаляем обложку которая будет в топ треке года
            $key = array_search($songOfYear['cover'], $sortedTrackCovers);
            unset($sortedTrackCovers[$key]);
            $sortedTrackCovers =  array_values($sortedTrackCovers);
            
            //получаем семь случайных чисел
            $randNums = Helpers::randomNumbers(0, count($sortedTrackCovers) - 1, 7);

            $covers = [];
            
            if(count($sortedTrackCovers) >= 10){
                for($i = 0; $i <= 6; $i++){
                    array_push($covers, $sortedTrackCovers[$randNums[$i]]);
                }
            }
            else{
                $covers = null;
            }
            //записываем пять случайных обложек
          
            $response['covers'] = $covers;
            $response['percent'] = $percent;

            $response['songOfYear'] = $songOfYear;

            return response()->json($response);
        }   
        else
        { return response()->json(false); }
    }

    //getDecadeMonth
    //посчитать любимую эпоху за месяц
    //возвращает json с эпохой
    //параметр: реквест
    public function getDecadeMonth(Request $request){
        //массив в который будут записаны треки
        $tracks = [];
    
        // получение треков прослушанных за последний месяц
     
        //проверяем токен
        $checkToken = System::checkSpotifyAccessToken($request);

        if($checkToken != false)
        {
            //получаем api
            $api = config('spotify_api');

            //смещение для получения треков
            $offset = 0;
            
            //пока смещение меньше 49, будем добавлять в массив треки 
            //(можно получить только 98 последних треков)
            //(больше не позволяет Spotify API, хз почему именно 98)
            while($offset < 98)
            {
                //получаем треки
                $tracksApi = $api->getMyTop('tracks', ['limit' => 49, 'time_range' => 'short_term', 'offset' => $offset])->items;

                //если треки есть
                if($tracksApi != null)
                {   
                    foreach($tracksApi as $track)
                    { array_push($tracks, $track); }
                }

                $offset += 49;  
            }

            //если треков меньше десяти, то возвращаем false
            if(count($tracks) < 10)
            { return response()->json("noTracks"); }
        }
        else
        { return response()->json(false); }

        //если он есть
        if($tracks != false && $tracks != null)
        {  
             //массив для всех десятилетий
             $allDecades = [];
              
             //запись всех десятилетий в массив
             foreach($tracks as $track)
             {
                 //получить год из даты выхода трека
                 $year = Helpers::getItemReleaseDate($track, "track", "short");
                 $decade = intval(substr($year, 0, 3) . "0");
                 array_push($allDecades, $decade); 
             }

            //подсчет кол-ва всех десятилетий
            $countDecades = [];

             foreach($allDecades as $decade)
             {   
                 $findDecade = array_key_exists($decade , $countDecades);
                 if($findDecade == false)
                 { $countDecades[$decade ] = 1; }
                 else
                 { $countDecades[$decade ] += 1; }
             }

             arsort($countDecades);

             if(count($countDecades) >= 3){
            
                $maxDecade = key($countDecades);
                $maxDecadeSongs = $countDecades[$maxDecade] . " " . Helpers::pickTheWord($countDecades[key($countDecades)], "песен", "песня", "песни");
                $word = Helpers::pickTheWord($countDecades[key($countDecades)], "прошло", "прошла", "прошло");
                //случайная песня
                $sortedTracks = [];
                foreach($tracks as $track){
                    $year = Helpers::getItemReleaseDate($track, "track", "short");
                    $decade = intval(substr($year, 0, 3) . "0");
                    if($decade == $maxDecade){
                        array_push($sortedTracks, [
                            'trackName' => Helpers::getFullNameOfItem($track, "fullname"),
                            'cover' => $track->album->images[0]->url,
                            'year' => $year,
                            'url' => $track->external_urls->spotify,
                        ]);
                    }
                }

                $top10Tracks = array_slice($sortedTracks, 0, 15);

                $randTrack = $top10Tracks[rand(0, 9)];
                
                $response = ['max' => $maxDecade, 'maxSongs'=> $maxDecadeSongs, 
                            'word'=>$word, 'maxSong'=>$randTrack];

                return response()->json($response);
             }
        }

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

            //получаем треки и записываем необходимую информацию о них
            $top10Tracks = $api->getMyTop('tracks', $options);
            
            //если треков меньше десяти, то возвращаем ошибку
            if(count($top10Tracks->items) < 10)
            { return response()->json('noTracks'); }
            else
            {  
                $count = 1;
                $tracks = [];
                foreach($top10Tracks->items as $track)
                {
                    $trackInfo = [];
                    $trackInfo['count'] = $count;
                    $trackInfo['id'] = $track->id;
                    $trackInfo['name'] = Helpers::getFullNameOfItem($track, "fullname");
                    $trackInfo['image'] = $track->album->images[2]->url;
                    $trackInfo['url'] = $track->external_urls->spotify;
                    $trackInfo['album'] = $track->album->name;
                    $trackInfo['album_url'] = $track->album->external_urls->spotify;
                    $trackInfo['album_year'] = Helpers::getItemReleaseDate($track, "track", "short");

                    array_push($tracks, $trackInfo);
                    $count++;
                }

                // случайная обложка
                $randTrackId = $tracks[rand(0, count($tracks) - 1)]['id'];
                $albumCover = $api->getTrack($randTrackId)->album->images[0]->url;

                $response = [];
                $response['items'] = $tracks;
                $response['backgroundImage'] = $albumCover;
    
                return response()->json($response);
            }
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

            //получаем треки и записываем необходимую информацию о них
            $top10Artists = $api->getMyTop('artists', $options);

            //если треков меньше десяти, то возвращаем ошибку
            if(count($top10Artists->items) < 10)
            {
                return response()->json('noArtists');
            }
            else
            {
                $count = 1;
                $artists = [];
                foreach($top10Artists->items as $artist)
                {
                    $artistInfo = [];
                    $artistInfo['count'] = $count;
                    $artistInfo['id'] = $artist->id;
                    $artistInfo['name'] = $artist->name;
                    $artistInfo['image'] = $artist->images[count($artist->images)-1]->url;
                    $artistInfo['genres'] = Helpers::getArtistsGenres($artist, 5);
                    $artistInfo['url'] = $artist->external_urls->spotify;
    
                    array_push($artists, $artistInfo);
                    $count++;
                }
    
                //случайное фото исполнителя
                $randArtistId = $artists[rand(0, count($artists) - 1)]['id'];
    
                $artistPhoto = $api->getArtist($randArtistId)->images[0]->url;
    
                $response['items'] = $artists;
    
                $response['backgroundImage'] = $artistPhoto;
    
                return response()->json($response);
            }
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
          if($tracks != false)
          {  
            //получаем полный список треков с id трека, длиной, обложкой, названием и url
            $tracksClean = [];

            foreach($tracks as $track)
            {   
                $trackInfo = [];
                $trackInfo['id'] = $track->id;
                $trackInfo['duration'] = Helpers::getTrackDuration($track->duration_ms);
                $trackInfo['image'] = $track->album->images[count($track->album->images) - 1]->url;
                $trackInfo['name'] = Helpers::getFullNameOfItem($track, "fullname");
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
            $response['items'] = $topTen;
     
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

    //getTop10TracksByPopularity
    //получить топ 10 треков по популярности
    //возвращает JSON с топ 10 самых популярных или не популярных треков
    //параметры: реквест, type - "popular" или "unpopular"
    public function getTop10TracksByPopularity(Request $request, $type)
    {
        //открываем файл с треками
        $tracks = System::getUserLibraryJson("tracks", $request);

        //если он есть
        if($tracks != false)
        {  
           //получаем все id треков вместе с процентом популярности
           $tracksClean = [];

           foreach($tracks as $track)
           {    
               $trackInfo = [];
               $trackInfo['id'] =  $track->id;
               $trackInfo['popularity'] = $track->popularity;
               $trackInfo['image'] = $track->album->images[count($track->album->images) - 1]->url;
               $trackInfo['name'] = Helpers::getFullNameOfItem($track, "fullname");
               $trackInfo['url'] = $track->external_urls->spotify;
               $trackInfo['album'] = $track->album->name;
               $trackInfo['album_url'] = $track->album->external_urls->spotify;
               $trackInfo['album_year'] = Helpers::getItemReleaseDate($track, "track", "short");

               array_push($tracksClean, $trackInfo);
           }

           //сортировка по ключу popularity
           $tracksSorted = "";
           $topTrackId = "";

           if($type == "popular")
           { 
               $tracksSorted = Helpers::sortArrayByKey($tracksClean, 'popularity', 'desc'); 
           }
           
           if($type == "unpopular")
           { 
               $tracksSorted = Helpers::sortArrayByKey($tracksClean, 'popularity', 'asc'); 
           }

           //получаем первые 10 элементов из списка
           $topTenTracks = array_slice($tracksSorted, 0, 10, true);

           for($i = 1; $i <= count($topTenTracks); $i++)
           {
             $topTenTracks[$i-1]['count'] = $i;
           }

           $checkToken = System::checkSpotifyAccessToken($request);

           if($checkToken != false)
           {
                $api = config('spotify_api');

                //случайная обложка
                $randTrackId = $topTenTracks[rand(0, count($topTenTracks) - 1)]['id'];
                $albumCover = $api->getTrack($randTrackId)->album->images[0]->url;

                $response = [];
                $response['items'] = $topTenTracks;
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
        if($tracks != false)
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
                    $artistInfo['name'] = $artist->name;
                    $artistInfo['image'] = $artist->images[count($artist->images)-1]->url;
                    $artistInfo['url'] = $artist->external_urls->spotify;
                    $artistInfo['info'] = $artistsCount[$artistIds[$i]] . " " . Helpers::pickTheWord($artistsCount[$artistIds[$i]], "треков", "трек", "трека");
    
                    array_push($artists, $artistInfo);
                }

                $response['items'] = $artists;

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
        if($tracks != false)
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
                    $artistInfo['name'] = $artist->name;
                    $artistInfo['image'] = $artist->images[count($artist->images)-1]->url;
                    $artistInfo['url'] = $artist->external_urls->spotify;
                    $artistInfo['info'] = Helpers::getDurationInHours($artistsCount[$artistIds[$i]]);
    
                    array_push($artists, $artistInfo);
                }

                $response['items'] = $artists;

                //получаем случайное фото исполнителя из топ 10
                $randomArtistId = $artistIds[rand(0, 9)];

                $response['backgroundImage'] = $api->getArtist($randomArtistId)->images[0]->url;

                return response()->json($response);
            }
        }
        else
        { return response()->json(false); }
    }

    //getMostListenedTrack
    //получить самый прослушиваемый трек за всё время или за месяц
    //возвращает JSON с самым прослушиваемым треком за все время или за месяц
    //параметры: реквест
    public function getMostListenedTrack(Request $request, $type)
    {
        //проверка токена
        $checkToken = System::checkSpotifyAccessToken($request);

        //если токен рабочий
        if($checkToken != false)
        {
            //получаем cамый прослушиваемый трек
            $api = config('spotify_api');

            $topTrack = "";

            $timeRange = "";

            switch($type){
                case "alltime":
                    $timeRange = "long_term";
                    break;
                case "month":
                    $timeRange = "short_term";
                    break;
                default:
                    $timeRange = "long_term";
            }

            $tracks = $api->getMyTop('tracks', ['limit' => 1, 'time_range' => $timeRange])->items;
            
            if(count($tracks) > 0)
            { 
                $topTrack = $tracks[0]; 
                
                $response = [
                    'title' => Helpers::getFullNameOfItem($topTrack, "fullname"),
                    'album' => $topTrack->album->name . " - ". Helpers::getItemReleaseDate($topTrack, "track", "short"),
                    'url' => $topTrack->external_urls->spotify,
                    'image' => $topTrack->album->images[0]->url,
                ];

                return response()->json($response);
            }
            else
            { return response()->json('noTracks'); }
        }
        else
        { return response()->json(false); }
    }

    //getMostPopularTrack
    //получить самый популярный или непопулярный трек из твоей библиотеки
    //возвращает JSON с самым популярным треком
    //параметры: реквест, type - "popular" или "unpopular"
    public function getTrackByPopularity(Request $request, $type)
    {
         //открываем файл с треками
         $tracks = System::getUserLibraryJson("tracks", $request);

         //если он есть
         if($tracks != false)
         {  
            //получаем все id треков вместе с процентом популярности
            $trackIds = [];

            foreach($tracks as $track)
            {
                array_push($trackIds, ['id' => $track->id, 'popularity' => $track->popularity]);
            }

            //сортировка по ключу popularity
            $tracksSorted = "";
            $topTrackId = "";

            if($type == "popular")
            { 
                $tracksSorted = Helpers::sortArrayByKey($trackIds, 'popularity', 'desc'); 
              
            }
            
            if($type == "unpopular")
            { 
                $tracksSorted = Helpers::sortArrayByKey($trackIds, 'popularity', 'asc'); 
            }

            $topTrackId = $tracksSorted[0]['id'];

            $checkToken = System::checkSpotifyAccessToken($request);

            if($checkToken != false)
            {
                $api = config('spotify_api');

                $track = $api->getTrack($topTrackId);

                $response = [
                    'title' => Helpers::getFullNameOfItem($track, "fullname"),
                    'album' => $track->album->name . " - ". Helpers::getItemReleaseDate($track, "track", "short"),
                    'url' => $track->external_urls->spotify,
                    'image' => $track->album->images[0]->url,
                ];

                return response()->json($response);
            }
            else
            { return response()->json(false); }
         }
         else
         { return response()->json(false); }
    }

    //getTrackByDuration
    //получить самый длинный или короткий трек
    //возвращает JSON с самым длинным или коротким треком
    //параметры: реквест, type - "long" или "short"
    public function getTrackByDuration(Request $request, $type)
    {
        //открываем файл с треками
        $tracks = System::getUserLibraryJson("tracks", $request);

        //если он есть
        if($tracks != false)
        {  
            //получаем id треков и их длину, сортируем
            $trackIds = [];

            // foreach($tracks as $track)
            // { array_push($trackIds, ['id' => $track->id, 'duration' => $track->duration_ms]); }

            foreach($tracks as $track)
            {
                $id = $track->id;
                $duration = Helpers::getTrackDuration($track->duration_ms);
                $cover = $track->album->images[count($track->album->images) - 1]->url;
                $name = Helpers::getFullNameOfItem($track, "fullname");
                $url = $track->external_urls->spotify;
                array_push($trackIds, ['id' => $id, 'duration' => $duration, 'cover' => $cover, 'name' => $name, 'url' => $url]);
            }

            $sortType = "desc";

            if($type == "long")
            { $sortType = "desc"; }

            if($type == "short")
            { $sortType = "asc"; }

            $trackIdsSorted = Helpers::sortArrayBYKey($trackIds, 'duration', $sortType);

            //проверяем токен
            $checkToken = System::checkSpotifyAccessToken($request);

            if($checkToken != false)
            {
                $api = config('spotify_api');

                $track = $api->getTrack($trackIdsSorted[0]['id']);

                $response = [
                    'title' => Helpers::getFullNameOfItem($track, "fullname"),
                    'album' => $track->album->name . " - ". Helpers::getItemReleaseDate($track, "track", "short"),
                    'url' => $track->external_urls->spotify,
                    'image' => $track->album->images[0]->url,
                    'additionalInfo' => "Длина - " . Helpers::getTrackDuration($track->duration_ms),
                ];

                return response()->json($response);
            }
        }
        else
        { return response()->json(false); }
    }

    //getMostListenedArtist
    //получить самого слушаемого исполнителя за все время или за месяц
    //возвращает JSON с самым слушаемым исполнителем
    //параметры: реквест, type - "alltime" или "month"
    public function getMostListenedArtist(Request $request, $type)
    {
        //проверка токена
        $checkToken = System::checkSpotifyAccessToken($request);

        //если токен действительный
        if($checkToken != false)
        {   
            //получаем api и самого слушаемого исполнителя
            $api = config('spotify_api');

            $timeRange = "long_term";

            if($type == "alltime")
            { $timeRange = "long_term"; }
            if($type == "month")
            { $timeRange = "short_term"; }

            $artists = $api->getMyTop('artists', ['limit' => 1, 'time_range' => $timeRange]);

            $topArtist = "";

            if(count($artists->items) > 0)
            {
                $topArtist = $artists->items[0];

                $response = [
                    'title' => $topArtist->name,
                    'url' => $topArtist->external_urls->spotify,
                    'image' => $topArtist->images[0]->url,
                    'additionalInfo' => Helpers::getArtistsGenres($topArtist, 5),
                ];
    
                return response()->json($response);
            }
            else
            {
                return response()->json('noArtists');
            }
        }
    }

    //getArtistByTracks 
    //получить исполнителя по кол-ву треков в библиотеке
    //возвращает JSON с исполнителем с наибольшим кол-вом треков в библиотеке
    //параметры: реквест
    public function getArtistByTracks(Request $request)
    {   
        //получаем файл с треками
        $tracks = System::getUserLibraryJson("tracks", $request);

        //если файл существует
        if($tracks != false)
        {   
            //получаем все id артистов из треков и считаем сколько раз встречается каждый из них
            $artistIds = [];

            foreach($tracks as $track)
            {   
                if(array_key_exists($track->artists[0]->id, $artistIds) == false)
                { $artistIds[$track->artists[0]->id] = 1; }
                else
                { $artistIds[$track->artists[0]->id] += 1; }
            }

            //сортируем массив по убыванию и получаем id артиста
            arsort($artistIds);

            $topArtistId = array_keys($artistIds)[0];

            //провнеряем токен и получаем информацию об артисте
            $checkToken = System::checkSpotifyAccessToken($request);

            if($checkToken != false)
            {
                $api = config('spotify_api');

                $topArtist = $api->getArtist($topArtistId);

                $trackCount = $artistIds[array_keys($artistIds)[0]];

                $response = [
                    'title' => $topArtist->name,
                    'url' => $topArtist->external_urls->spotify,
                    'image' => $topArtist->images[0]->url,
                    'additionalInfo' => $trackCount . " " . Helpers::pickTheWord($trackCount, "треков", "трек", "трека") . " в библиотеке", 
                ];

                return response()->json($response);
            }
            else
            { return response()->json(false); }
        }
        else
        { return response()->json(false); }
    }

    //getArtistByTime
    //получить артиста с наибольшим кол-вом времени треков в библиотеке
    //возвращает JSON с артистом с наибольшим кол-вом времени
    //параметры: реквест
    public function getArtistByTime(Request $request)
    {
        //получаем файл треков
        $tracks = System::getUserLibraryJson("tracks", $request);

        //если файл существует
        if($tracks != false)
        {
            //получаем id исполнителей вместе с общей длительностью
            $artistIds = [];

            foreach($tracks as $track)
            {
                if(array_key_exists($track->artists[0]->id, $artistIds) == false)
                { $artistIds[$track->artists[0]->id] = $track->duration_ms; }
                else
                { $artistIds[$track->artists[0]->id] += $track->duration_ms; }
            }

            //сортируем массив и получаем id исполнителя и время
            arsort($artistIds);

            $topArtistId = array_keys($artistIds)[0];

            $time = Helpers::getDurationInHours($artistIds[$topArtistId]);

            //проверяем токен и получаем инфомацию об исполнителей
            $checkToken = System::checkSpotifyAccessToken($request);

            if($checkToken != false)
            {
                $api = config('spotify_api');

                $topArtist = $api->getArtist($topArtistId);

                $response = [
                    'title' => $topArtist->name,
                    'url' => $topArtist->external_urls->spotify,
                    'image' => $topArtist->images[0]->url,
                    'additionalInfo' => $time, 
                ];

                return response()->json($response);
            }
        }
        else
        { return response()->json(false); }
    }

    //getArtistByPopularity
    //получить самого популярного или непопулярного артиста из библиотеки
    //возвращает JSON с самым популярным или непопулярным артистом
    //параметры: реквест, type - "popular" или "unpopular"
    public function getArtistByPopularity(Request $request, $type)
    {
        //получаем файл треков
        $artists = System::getUserLibraryJson("artists", $request);

        if($artists != false)
        {      
            //получаем список артистов с нужной иформацией
            $artistsClean = [];

            foreach($artists as $artist)
            {
                $artistInfo = [];

                $artistInfo['name'] = $artist->name;
                $artistInfo['url'] = $artist->external_urls->spotify;
                $artistInfo['image'] = $artist->images[0]->url;
                $artistInfo['popularity'] = $artist->popularity;
                $artistInfo['genres'] = Helpers::getArtistsGenres($artist, 5);

                array_push($artistsClean, $artistInfo);
            }

            //сортируем в нужном порядке
            $artistsSorted = "";

            if($type == "popular")
            { $artistsSorted = Helpers::sortArrayByKey($artistsClean, "popularity", "desc"); }
            else if($type == "unpopular")
            { $artistsSorted = Helpers::sortArrayByKey($artistsClean, "popularity", "asc"); }
            else
            { return response()->json(false); }

            $topArtist = $artistsSorted[0];

            //находим треки исполнителя
            $tracks = System::getUserLibraryJson("tracks", $request);

            $allArtists = [];
            foreach($tracks as $track){
                array_push($allArtists, ['artist' => $track->artists[0]->name, 
                                            'track' => $track->name,
                                            'url' => $track->external_urls->spotify]);
            }

            $selectedTracks = [];

            foreach($allArtists as $artist){
                if($artist['artist'] === $topArtist['name']){
                    array_push($selectedTracks, $artist);
                }
            }

            $trackCount = count($selectedTracks) . " " . Helpers::pickTheWord(count($selectedTracks), "треков", "трек", "трека");
            if(count($selectedTracks) > 0){
                $selectedTrack = $selectedTracks[rand(0, count($selectedTracks) - 1)];
            }
            else{
                $selectedTrack = null;
            }
            

            $response = [
                'title' => $topArtist['name'],
                'url' => $topArtist['url'],
                'image' => $topArtist['image'],
                'additionalInfo' => $topArtist['genres'],
                'trackCount' => $trackCount,
                'selectedTrack' => $selectedTrack['track'],
                'trackUrl' => $selectedTrack['url'],
            ];

            return response()->json($response);
        }
        else 
        { return response()->json(false); }

    }

    //getLatestTracks
    //получить последние прослушанные треки
    //возвращает JSON с последними прослушанными треками
    //параметры: реквест
    public function getLatestTracks(Request $request)
    {   
        //проверка токена
        $checkToken = System::checkSpotifyAccessToken($request);

        if($checkToken != false)
        {
            $api = config('spotify_api');

            $latestTracks = $api->getMyRecentTracks(['limit' => 50]);
            // dd($latestTracks);
            $tracks = [];

            foreach($latestTracks->items as $item)
            {
                $track = $item->track;
                $trackInfo = [];
                $trackInfo['id'] = rand(0,999).rand(0,9999);
                $trackInfo['artists'] = Helpers::getFullNameOfItem($track, "artist");
                $trackInfo['trackName'] = $track->name;
                $trackInfo['albumName'] = $track->album->name . " - " . Helpers::getItemReleaseDate($track->album, "album", "short");
                $trackInfo['trackUrl'] = $track->external_urls->spotify;
                $trackInfo['albumUrl'] = $track->album->external_urls->spotify;
                $trackInfo['duration'] = Helpers::getTrackDuration($track->duration_ms);
                $trackInfo['cover'] = $track->album->images[0]->url;

                array_push($tracks, $trackInfo);
            }

            $response['tracks'] = $tracks;

            $response['backgroundImage'] = $tracks[rand(0, count($tracks) - 1)]['cover'];

            return response()->json($response);
        }
        else
        { return response()->json(false); }
    }

}
