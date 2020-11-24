<?php

    namespace App\Globals;
    use Auth;
    use Carbon\Carbon;
    use SpotifyWebAPI;
    use Cookie;
    use File;

    //глобальные функции на разные случаи
    class Globals
    {
           //проверка того что токен существует и он не просрочен
           //в параметре нужен реквест, из него берутся куки
            public static function checkSpotifyAccessToken($request)
            {   
                $response = [];

                //Spotify access token
                $spotifyAccessToken = $request->cookie('spotify_access_token');

                //если токен существует
                if($spotifyAccessToken != null)
                {
                    $spotifyAccessExpiration = $request->cookie('spotify_access_expiration');

                    //если время действия токена истекло
                    if(Carbon::now() >= $spotifyAccessExpiration)
                    {
                        $session = new SpotifyWebAPI\Session(
                            config('settings')->spotify_client_id,
                            config('settings')->spotify_client_secret,
                            config('settings')->spotify_redirect_uri
                        );
                
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

                        //устанавливаем новый токен
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
                {
                    return false;
                }
            }

            //получение файла с библиотекой пользователя
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

                $json = json_decode($file);

                return $json;
            }

            //выбрать правильно окончание для слова (десять минуТ, две минуТЫ, одна минуТА и т.п)
            //число, первый вариант окончания (5 минуТ, 0 минуТ, 10 минуТ), второй вариант окончания (1 минуТА), третий вариант окончания (2, 3, 4 минуТЫ)
            public static function pickTheWord($number, $firstWord, $secondWord, $thirdWord)
            {
                $lastNumber = strval($number)[strlen(strval($number)) - 1];

                if($lastNumber == "1")
                { return " " . $secondWord; }
                elseif($lastNumber == "2" || $lastNumber == "3" || $lastNumber == "4")
                { return " " . $thirdWord; }
                else
                { return " " . $firstWord;}
            }

            //получить название трека или альбома (исполнители через запятую + название)
            public static function getFullName($item)
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

            //функция сортировки по убыванию и по возрастанию
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

            //сортировка массива по ключу
            public static function sortArrayByKey($array, $key, $order)
            {
                $arrayCopy = $array;

                usort($arrayCopy, Globals::sortFunction($key, $order));

                return $arrayCopy;
            }
    }

?>