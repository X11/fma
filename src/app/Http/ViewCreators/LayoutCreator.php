<?php

namespace App\Http\ViewCreators;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\User;

class LayoutCreator
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

        $view->with('THEME', $settings->theme);
        $view->with('TVDB_LOAD_HD', $settings->tvdb_load_hd);
    }
    
    
}
