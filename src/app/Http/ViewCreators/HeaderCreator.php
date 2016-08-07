<?php

namespace App\Http\ViewCreators;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\User;

class HeaderCreator
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
        $view->with('header_background', Auth::check()
                                            ? Auth::user()->settings->header
                                            : User::$BASE_SETTINGS['header']);
    }
    
    
}
