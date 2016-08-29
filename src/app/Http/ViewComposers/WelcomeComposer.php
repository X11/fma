<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use Carbon\Carbon;
use App\Episode;

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
        ];
        $view->with('fanart', $fanarts[array_rand($fanarts)]);


        $last_aired = Episode::with('serie')
                                ->where('aired', Carbon::today()->toDateString())
                                ->orderBy('rating', 'asc')
                                ->first();

        $view->with('last_aired', $last_aired);
    }
    
}
