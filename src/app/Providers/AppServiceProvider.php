<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Auth;
use App\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->creator('layouts/app', function($view){
            $view->with('theme', Auth::check()
                                    ? Auth::user()->settings->theme
                                    : User::$BASE_SETTINGS['theme']);
        });

        view()->creator('partial/header', function($view){
            $view->with('header_background', Auth::check()
                                    ? Auth::user()->settings->header
                                    : User::$BASE_SETTINGS['header']);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
