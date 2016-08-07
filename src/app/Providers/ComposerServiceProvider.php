<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('welcome', 'App\Http\ViewComposers\WelcomeComposer');

        view()->composer('calendar/index', 'App\Http\ViewComposers\CalendarComposer');

        view()->composer('serie/index', 'App\Http\ViewComposers\SerieIndexComposer');

        view()->composer('admin/stats', 'App\Http\ViewComposers\AdminStatsComposer');
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
