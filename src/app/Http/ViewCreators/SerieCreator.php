<?php

namespace App\Http\ViewCreators;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\User;

class SerieCreator
{

    /**
     * @param mixed 
     */
    public function __construct()
    {
        //
    }

    /**
     * Bind data to view
     *
     * @return void
     */
    public function create(View $view)
    {
        $settings = Auth::check()
                    ? Auth::user()->settings
                    : (object) User::$BASE_SETTINGS;

        $view->with('overview_container', $settings->serie_overview);
        $view->with('serie_fanart', $settings->serie_fanart);
        $view->with('serie_actor_images', $settings->serie_actor_images == 'yes');
    }
    
    
}
