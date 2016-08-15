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

        $serieCountStats = DB::table('stats')
                                ->where('key', 'serie.count')
                                ->orderBy('created_at', 'desc')
                                ->limit($limit)
                                ->get();

        $episodeCountStats = DB::table('stats')
                                ->where('key', 'episode.count')
                                ->orderBy('created_at', 'desc')
                                ->limit($limit)
                                ->get();

        $userCountStats = DB::table('stats')
                                ->where('key', 'user.count')
                                ->orderBy('created_at', 'desc')
                                ->limit($limit)
                                ->get();

        $peopleCountStats = DB::table('stats')
                                ->where('key', 'person.count')
                                ->orderBy('created_at', 'desc')
                                ->limit($limit)
                                ->get();

        $loginStats = DB::table('stats')
                            ->where('key', 'logins')
                            ->orderBy('created_at', 'desc')
                            ->limit($limit)
                            ->get();

        $episodeWatchedStats = DB::table('stats')
                                ->where('key', 'episode.watched')
                                ->orderBy('created_at', 'desc')
                                ->limit($limit)
                                ->get();

        $view->with('serieCountStats', collect($serieCountStats)->each(function($item){$item->created_at = '"'.$item->created_at.'"';return $item;})->reverse());
        $view->with('episodeCountStats', collect($episodeCountStats)->each(function($item){$item->created_at = '"'.$item->created_at.'"';return $item;})->reverse());
        $view->with('userCountStats', collect($userCountStats)->each(function($item){$item->created_at = '"'.$item->created_at.'"';return $item;})->reverse());
        $view->with('peopleCountStats', collect($peopleCountStats)->each(function($item){$item->created_at = '"'.$item->created_at.'"';return $item;})->reverse());
        $view->with('episodeWatchedStats', collect($episodeWatchedStats)->each(function($item){$item->created_at = '"'.$item->created_at.'"';return $item;})->reverse());
        $view->with('loginStats', collect($loginStats)->each(function($item){$item->created_at = '"'.$item->created_at.'"';return $item;}));
        $view->with('serieCount', Serie::count());
        $view->with('episodeCount', Episode::count());
        $view->with('userCount', User::count());
        $view->with('peopleCount', Person::count());
        $view->with('jobCount', DB::table('jobs')->selectRaw('COUNT(*) as aggregate')->first()->aggregate);
        $view->with('failedJobCount', DB::table('failed_jobs')->selectRaw('COUNT(*) as aggregate')->first()->aggregate);
    }
    
}
