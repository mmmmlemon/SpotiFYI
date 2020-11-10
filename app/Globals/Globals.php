<?php

    namespace App\Globals;
    use Auth;
    use Carbon\Carbon;
    use SpotifyWebAPI;
    use Cookie;

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
                            ],
                        ];

                        //обновляем токен, рефреш токен и время действия токена
                        $session->refreshAccessToken($request->cookie('spotify_refresh_token'));
                        $newAccessToken = $session->getAccessToken();
                        $newRefreshToken = $session->getRefreshToken();
                        $accessExpiration = Carbon::now()->addMinutes(50);
                        //записывем новые токены в куки
                        Cookie::queue('spotify_access_token',  $newAccessToken, 60*24*30*12);
                        Cookie::queue('spotify_refresh_token', $newRefreshToken, 60*24*30*12);
                        Cookie::queue('spotify_access_expiration', $accessExpiration, 60*24*30*12);

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
    }

?>