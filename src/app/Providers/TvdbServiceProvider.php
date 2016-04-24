<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use FPN\TheTVDB\Api;
use FPN\TheTVDB\HttpClient\Buzz;

class TvdbServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
         $this->app->bind('tvdb', function () {
             $httpClient = new Buzz();
             return new Api($httpClient, env('TVDB_KEY'));
        });
    }
}
