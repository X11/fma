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
            $settings = Auth::check()
                        ? Auth::user()->settings
                        : (object) User::$BASE_SETTINGS;

            $view->with('serie_fanart', $settings->serie_fanart);
            $view->with('serie_actor_images', $settings->serie_actor_images == 'yes');
        });

        view()->composer('calender/index', function ($view) {
            $view->with('overview_container', Auth::check()
                                                ? Auth::user()->settings->calender_overview
                                                : User::$BASE_SETTINGS['calender_overview']);

            $watching_ids = Auth::check()
                             ? Auth::user()
                                    ->watching
                                    ->pluck('id')
                                    ->toArray()
                             : [];

            $view->with('watching_ids', $watching_ids);
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
