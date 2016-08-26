<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;

class WelcomeComposer
{

    /**
     * @param mixed 
     */
    public function __construct()
    {
        //
    }

    /**
     * undocumented function
     *
     * @return void
     */
    public function compose(View $view)
    {
        $fanarts = [
            '//thetvdb.com/banners/fanart/original/259765-12.jpg',
            '//thetvdb.com/banners/fanart/original/248742-8.jpg',
            '//thetvdb.com/banners/fanart/original/289590-20.jpg',
            '//thetvdb.com/banners/fanart/original/269533-14.jpg',
            '//thetvdb.com/banners/fanart/original/248835-3.jpg',
        ];
        $view->with('fanart', $fanarts[array_rand($fanarts)]);
    }
    
}
