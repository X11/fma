<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Activity;

class WatchlistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filters = collect(Auth::user()->settings->watchlist_filters);

        $series = Auth::user()->watching->sortBy('name');

        $items = DB::table('watchlist')
            ->join('series', 'series.id', '=', 'watchlist.serie_id')
            ->join('episodes', 'episodes.serie_id', '=', 'series.id')
            ->leftJoin('episodes_watched', 'episodes_watched.episode_id', '=', 'episodes.id')
            ->select(
                'series.name as serie_name',
                'episodes.name as episode_name',
                'episodes.id as episode_id',
                'series.id as serie_id',
                'episodes.episodeNumber as episode_number',
                'episodes.episodeSeason as episode_season',
                'episodes.aired as episode_aired'
            )
            ->where([
                ['episodes.aired', '!=', ''],
                ['episodes.aired', '<', Carbon::today()],
                ['episodes.episodeSeason', '>', 0],
                ['watchlist.user_id', '=', Auth::user()->id],
            ])
            ->whereNotIn('series.id', $filters)
            ->when($request->input('q'), function($query) use ($request){
                return $query->where('series.name', 'LIKE', '%' . $request->input('q') . '%');
            })
            ->whereNull('episodes_watched.episode_id')
            ->orderBy('episodes.aired', 'desc')
            ->paginate(max(10, $series->count()/2));

        $series_episode_count = DB::table('watchlist')
            ->join('series', 'series.id', '=', 'watchlist.serie_id')
            ->join('episodes', 'episodes.serie_id', '=', 'series.id')
            ->leftJoin('episodes_watched', 'episodes_watched.episode_id', '=', 'episodes.id')
            ->select(
                DB::raw('count(*) as episode_count, series.id')
            )
            ->where([
                ['episodes.aired', '!=', ''],
                ['episodes.aired', '<', Carbon::today()],
                ['episodes.episodeSeason', '>', 0],
                ['watchlist.user_id', '=', Auth::user()->id],
            ])
            ->whereNull('episodes_watched.episode_id')
            ->groupBy('id')
            ->get();

        $series_episode_count = collect($series_episode_count)
            ->groupBy('id')
            ->map(function ($a) {
                return $a->first()->episode_count;
            });

        $items->appends(['q' => $request->input('q')]);

        return view('watchlist.index')
            ->with('ajax', $request->ajax())
            ->with('query', $request->input('q'))
            ->with('series', $series)
            ->with('series_episode_count', $series_episode_count)
            ->with('filters', $filters)
            ->with('items', $items);
    }

    /**
     * Add a serie to the watchlist.
     *
     * @return \Illuminate\Http\Response
     */
    public function add(Request $request, $id)
    {
        Auth::user()->watching()->attach($id);

        Activity::log('serie.track', $id);

        return response()->json([
            'status' => 'Added to watchlist',
        ]);
    }

    /**
     * Remove a serie from the watchlist.
     *
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, $id)
    {
        Auth::user()->watching()->detach($id);

        Activity::log('serie.untrack', $id);

        return response()->json([
            'status' => 'Removed from watchlist',
        ]);
    }
}
