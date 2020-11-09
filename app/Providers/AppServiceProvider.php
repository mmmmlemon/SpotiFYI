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
        $settings = App\Settings::all()[0];
        config(['settings' => $settings]);

        $api = new SpotifyWebAPI\SpotifyWebAPI();

        config(['api' => $api]);
    }
}
