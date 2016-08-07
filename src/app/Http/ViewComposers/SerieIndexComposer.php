<?php

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use App\Genre;

class SerieIndexComposer 
{

    /**
     * @param mixed 
     */
    public function __construct()
    {
    }

    /**
     * Bind data to view
     *
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('query', request()->input('q'));
        $view->with('_sort', request()->input('_sort'));
        $view->with('_genre', request()->input('_genre'));
        $view->with('genres', Genre::has('series')->get());
    }
    
}
