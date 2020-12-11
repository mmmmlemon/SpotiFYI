<?php

    namespace App\Globals;
    use Auth;
    use Carbon\Carbon;
    use SpotifyWebAPI;
    use Cookie;
    use File;

    //Globals
    //глобальные функции на разные случаи
    class Globals
    {
           //checkSpotifyAccessToken
           //проверка того что токен доступа к Spotify API существует и он не просрочен
           //в параметре нужен реквест, из него берутся куки, в которых должен храниться токен
           //возвращает true если токен есть и он не просрочен, либо если просрочен и он успешно обновился
           //возвращает false если токена не существует
            public static function checkSpotifyAccessToken($request)
            {   
                //берем Spotify access token из куки
                $spotifyAccessToken = $request->cookie('spotify_access_token');

                //если токен существует
                if($spotifyAccessToken != null)
                {
                    //получаем дату просрочки токена
                    $spotifyAccessExpiration = $request->cookie('spotify_access_expiration');

                    //если время действия токена истекло
                    if(Carbon::now() >= $spotifyAccessExpiration)
                    {
                        //создаем новую сессию Spotify API
                        $session = new SpotifyWebAPI\Session(
                            config('settings')->spotify_client_id,
                            config('settings')->spotify_client_secret,
                            config('settings')->spotify_redirect_uri
                        );
                        
                        //задаем опции
                        $options = [
                            'scope' => [
                                'playlist-read-private',
                                'user-read-private',
                                'user-library-read',
                                'user-follow-read',
                            ],
                        ];

                        //обновляем токен, рефреш токен и время действия токена
                        $session->refreshAccessToken($request->cookie('spotify_refresh_token'));
                        $newAccessToken = $session->getAccessToken();
                        $newRefreshToken = $session->getRefreshToken();
                        $accessExpiration = Carbon::now()->addMinutes(50);
                        $chars = "ABCDEFG";
                        $randName = $chars[rand(0, 6)] . rand(0,9) . $chars[rand(0, 6)] . rand(0,9) . $chars[rand(0, 6)] . rand(0,9);
                        
                        //записывем новые токены в куки
                        Cookie::queue('spotify_access_token',  $newAccessToken, 60*24*30*12);
                        Cookie::queue('spotify_refresh_token', $newRefreshToken, 60*24*30*12);
                        Cookie::queue('spotify_access_expiration', $accessExpiration, 60*24*30*12);
                        Cookie::queue('rand_name', $randName, 60*24*30);

                        //устанавливаем новый токен в сессию
                        config('spotify_api')->setAccessToken($newAccessToken);

                        //возвращаем true
                        return true;
                    }
                    else
                    {
                        //если не истекло, то устанавливаем токен и возвращаем true
                        config('spotify_api')->setAccessToken($spotifyAccessToken);
                        return true;
                    }
                }
                //если не существует, возвращаем false
                else
                { return false; }
            }

            //getUserLibraryJson
            //получение JSON'а из файлов с треками\альбомами\подписками пользователя, 
            //которые сохраняются в storage во время подсчета статистики
            //в параметрах: имя файла и реквест, из которого берется имя папки в которую сохраняются эти файлы
            //возвращает готовый к обработке JSON, либо false если файл не удалось открыть
            public static function getUserLibraryJson($filename, $request)
            {
                //открываем файл
                $file = "";
                try{
                    $file = File::get(storage_path("app/public/user_libraries/" . $request->cookie('rand_name') . "/" . $filename . ".json"));
                } 
                catch (FileNotFoundException $e) {
                    //если нет такого файла, то возвращаем false
                    return response()->json(false);
                }

                //декодируем json из файла
                $json = json_decode($file);

                return $json;
            }

            //pickTheWord
            //выбрать правильное окончание для словосочетания (десять минуТ, две минуТЫ, одна минуТА и т.п)
            //в параметрах: число, первый вариант окончания (5 минуТ, 0 минуТ, 10 минуТ), второй вариант окончания (1 минуТА), третий вариант окончания (2, 3, 4 минуТЫ)
            //возвращает строку со словосочетанием, либо false если в параметре $number не было указано целое число
            public static function pickTheWord($number, $firstWord, $secondWord, $thirdWord)
            {
                //проверяем содержит ли $number число
                //если нет, то возвращаем false
                if(is_numeric($number) == false)
                { 
                    return response()->json(false); 
                }
                else
                {
                    //получаем последнюю цифру в числе
                    $lastNumber = strval($number)[strlen(strval($number)) - 1];

                    if($lastNumber == "1")
                    { 
                        return " " . $secondWord; 
                    }
                    elseif($lastNumber == "2" || $lastNumber == "3" || $lastNumber == "4")
                    { 
                        return " " . $thirdWord; 
                    }
                    else
                    { 
                        return " " . $firstWord;
                    }
                }
            }

            //getFullNameOfItem
            //получить полное название трека или альбома 
            //исполнители через запятую + название, например "Queen, David Bowie - Under Pressure"
            //в параметре трек или альбом из JSON'а Spotify API
            //возвращает строку с полным названием, либо false
            public static function getFullNameOfItem($item)
            {   
                if(count($item->artists) > 0)
                {
                    $artists = "";
                
                    for($j = 1; $j <= count($item->artists); $j++)
                    {
                        if($j != count($item->artists) && count($item->artists) > 1)
                        { $artists .= $item->artists[$j-1]->name . ", ";}
                        else
                        { $artists .= $item->artists[$j-1]->name; }
                    }
        
                    return $artists . " - " . $item->name;
                }
                else
                { return response()->json(false); } 
            }

            //sortFunction
            //функция сортировки по ключу, по убыванию или по возрастанию
            //параметры: ключ и порядок сортировки
            //возвращает отсортированные массив, либо false если не был задан верный порядок сортировки
            private static function sortFunction($key, $order) 
            {
                if($order == "asc") //если порядок - по возрастанию
                {
                    return function ($a, $b) use ($key) {
                        return strnatcmp($a[$key], $b[$key]);
                    };
                }
                else if($order == "desc") //если порядок - по убыванию
                {
                    return function ($a, $b) use ($key) {
                        return strnatcmp($b[$key], $a[$key]);
                    };
                }
                else
                { return false; } 
            }

            //sortArrayByKey
            //сортировка массива по ключу
            //параметры: массив, ключ, порядок сортировки
            public static function sortArrayByKey($array, $key, $order)
            {
                $arrayCopy = $array;

                usort($arrayCopy, Globals::sortFunction($key, $order));

                return $arrayCopy;
            }
            
            //randomHslColor
            //сгенерировать случайный цвет в формате HSL
            //параметр: сдвиг по оси цветов
            //возвращает строку с цветом в формате HSL
            public static function randomHslColor($param)
            {   
                $offset = 0;

                if($param['offset'] != null)
                { $offset = $param['offset']; }

                //генерируем цвет в RGB
                $randNum = rand($offset,360);

                //записываем в строку, параметры saturation, brightness и opacity 
                //будут всегда одинаковые чтобы цвет был яркий
                $hslColor = "hsla(".$randNum.",100%,39%,1)";

                return $hslColor;
            }

            //trackDurationToMinutes
            //перевести длительность трека из миллисекунд в минуты и секунды
            //возвращает строку с длиной трека в минутах и секундах
            //параметры: длина трека в миллисекундах
            public static function trackDurationToMinutes($durationMs)
            {
                if(is_numeric($durationMs))
                {
                    $durationS = $durationMs / 1000;
                    $durationMinutes = round($durationS / 60, 3);
                    
                    $durationSeconds = floor(60 * ($durationMinutes - floor($durationMinutes)));
    
                    $minutesStr = strval(floor($durationMinutes));
                    $secondsStr = strval($durationSeconds);
                    
                    if(strlen($secondsStr) == 1)
                    { $secondsStr = "0" . $secondsStr; }
    
                    $durationStr = $minutesStr.":".$secondsStr;
    
                    return $durationStr;
                }
                else
                { return false;  }

            }
    }

?>