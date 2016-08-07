<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class CreatorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->creator('layouts/app', 'App\Http\ViewCreators\LayoutCreator');

        view()->creator('partial/header', 'App\Http\ViewCreators\HeaderCreator');

        view()->creator(['serie/show', 'serie/index'], 'App\Http\ViewCreators\SerieCreator'); 
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
