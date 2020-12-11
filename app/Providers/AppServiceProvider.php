<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App;
use Illuminate\Http\Request;
use SpotifyWebAPI;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Request $request)
    {
        //получение настроек сайта из БД
        $settings = App\Settings::all()[0];
        config(['settings' => $settings]);
        
        //сессия подключения к Spotify API
        //доступна из любого контроллера
        $api = new SpotifyWebAPI\SpotifyWebAPI();
        config(['spotify_api' => $api]);

    }
}
