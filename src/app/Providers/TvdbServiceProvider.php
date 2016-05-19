<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Adrenth\Thetvdb\Client;
use Cache;

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
         $this->app->singleton('tvdb', function () {
            $client = new Client();
            $client->setLanguage('en');

            $token = Cache::get('tvdb_token', function() use ($client){
                $t = $client->authentication()->login(env('TVDB_KEY'), null, null);
                Cache::put('tvdb_token', $t, 1200);
                return $t;
            }, 1200);

            $client->setToken($token);

            return $client;
        });
    }
}
