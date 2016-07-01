<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Auth;
use App\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        view()->creator('layouts/app', function ($view) {
            $settings = Auth::check()
                        ? Auth::user()->settings
                        : (object) User::$BASE_SETTINGS;

            $view->with('THEME', $settings->theme);
            $view->with('TVDB_LOAD_HD', $settings->tvdb_load_hd);
        });

        view()->creator('partial/header', function ($view) {
            $view->with('header_background', Auth::check()
                                    ? Auth::user()->settings->header
                                    : User::$BASE_SETTINGS['header']);
        });

        view()->creator('serie/index', function ($view) {
            $view->with('overview_container', Auth::check()
                                    ? Auth::user()->settings->serie_overview
                                    : User::$BASE_SETTINGS['serie_overview']);
        });

        view()->creator('serie/show', function ($view) {
            $view->with('serie_fanart', Auth::check()
                                    ? Auth::user()->settings->serie_fanart
                                    : User::$BASE_SETTINGS['serie_fanart']);
        });

        view()->creator('calender/index', function ($view) {
            $view->with('overview_container', Auth::check()
                                    ? Auth::user()->settings->calender_overview
                                    : User::$BASE_SETTINGS['calender_overview']);
        });
    }

    /**
     * Register any application services.
     */
    public function register()
    {
        //
    }
}
