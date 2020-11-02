<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App;

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
    public function boot()
    {
        $settings = App\Settings::all()[0];
        config(['settings' => $settings]);
    }
}
