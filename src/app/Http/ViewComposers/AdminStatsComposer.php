<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use App\Serie;
use App\Episode;
use App\User;
use App\Person;

class AdminStatsComposer
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
        $limit = 14;
        $chars = [];

        /**
         * Create an char for the serie counts
         *
         */
        $serieCountStats = collect(DB::table('stats')
                                        ->where('key', 'serie.count')
                                        ->orderBy('created_at', 'desc')
                                        ->limit($limit)
                                        ->get()
                                    )->each(function($item){$item->created_at = '"'.$item->created_at.'"';return $item;})
                                    ->reverse();

        $chars['series'] = [
            'title' => "Series",
            'current' => Serie::count(),
            'labels' => $serieCountStats->implode('created_at', ','),
            'data' => $serieCountStats->implode('value', ','),
        ];

        /**
         * Create an char for episodes count
         *
         */
        $episodeCountStats = collect(DB::table('stats')
                                        ->where('key', 'episode.count')
                                        ->orderBy('created_at', 'desc')
                                        ->limit($limit)
                                        ->get()
                                )->each(function($item){$item->created_at = '"'.$item->created_at.'"';return $item;})
                                ->reverse();

        $chars['episodes'] = [
            'title' => "Episodes",
            'current' => Episode::count(),
            'labels' => $episodeCountStats->implode('created_at', ','),
            'data' => $episodeCountStats->implode('value', ','),
        ];

        /**
         * Create an char for user count
         *
         */
        $userCountStats = collect(DB::table('stats')
                                    ->where('key', 'user.count')
                                    ->orderBy('created_at', 'desc')
                                    ->limit($limit)
                                    ->get()
                                )->each(function($item){$item->created_at = '"'.$item->created_at.'"';return $item;})
                                ->reverse();

        $chars['users'] = [
            'title' => "Users",
            'current' => User::count(),
            'labels' => $userCountStats->implode('created_at', ','),
            'data' => $userCountStats->implode('value', ','),
        ];

        /**
         * Create an char for the people count
         *
         */
        $peopleCountStats = collect(DB::table('stats')
                                        ->where('key', 'person.count')
                                        ->orderBy('created_at', 'desc')
                                        ->limit($limit)
                                        ->get()
                                )->each(function($item){$item->created_at = '"'.$item->created_at.'"';return $item;})
                                ->reverse();

        $chars['people'] = [
            'title' => "People",
            'current' => Person::count(),
            'labels' => $peopleCountStats->implode('created_at', ','),
            'data' => $peopleCountStats->implode('value', ','),
        ];

        /**
         * Create an char for the logins
         *
         */
        $loginStats = collect(DB::table('stats')
                                ->where('key', 'logins')
                                ->orderBy('created_at', 'desc')
                                ->limit($limit)
                                ->get()
                            )->each(function($item){$item->created_at = '"'.$item->created_at.'"';return $item;})
                            ->reverse();

        $chars['logins'] = [
            'title' => "Logins",
            'current' => null,
            'labels' => $loginStats->implode('created_at', ','),
            'data' => $loginStats->implode('value', ','),
        ];

        /**
         * Create an char for the episodes watched
         *
         */
        $episodeWatchedStats = collect(DB::table('stats')
                                            ->where('key', 'episode.watched')
                                            ->orderBy('created_at', 'desc')
                                            ->limit($limit)
                                            ->get()
                                )->each(function($item){$item->created_at = '"'.$item->created_at.'"';return $item;})
                                ->reverse();

        $chars['watched'] = [
            'title' => 'Watched episodes',
            'current' => null,
            'labels' => $episodeWatchedStats->implode('created_at', ','),
            'data' => $episodeWatchedStats->implode('value', ','),
        ];

        $view->with('jobCount', DB::table('jobs')->selectRaw('COUNT(*) as aggregate')->first()->aggregate);
        $view->with('failedJobCount', DB::table('failed_jobs')->selectRaw('COUNT(*) as aggregate')->first()->aggregate);
        $view->with('chars', $chars);
    }
    
}
