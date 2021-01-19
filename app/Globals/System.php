<?php

    namespace App\Globals;
    use Auth;
    use Carbon\Carbon;
    use SpotifyWebAPI;
    use Cookie;
    use File;

    //Globals
    //глобальные функции для работы со Spotify API и файлами
    class System
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
                    //получаем имя папки с данными пользователя из Cookies
                    $folderName = $request->cookie('rand_name');

                    //проверяем что папка существует
                    $check = File::exists(storage_path("app/public/user_libraries/".$folderName));

                    if($check == true)
                    {
                        Storage::disk('public')->deleteDirectory("user_libraries/".$folderName);

                        return response()->json(true);
                    }

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
    }
?>