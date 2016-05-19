<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class WatchlistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $filter = $request->input('_filter');

        $items = DB::table('watchlist')
            ->join('series','series.id', '=', 'watchlist.serie_id')
            ->join('episodes','episodes.serie_id', '=', 'series.id')
            ->leftJoin('episodes_watched','episodes_watched.episode_id', '=', 'episodes.id')
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
                ['watchlist.user_id', '=', Auth::user()->id]
            ])
            ->when($filter, function($query) use ($filter){
                return $query->whereIn('series.id', explode(',', $filter));
            })
            ->whereNull('episodes_watched.episode_id')
            ->orderBy('episodes.aired', 'desc')
            ->paginate(50);

        $series = Auth::user()->watching->sortBy('name');

        if ($filter){
            $items->appends(['_filter' => $filter]);
        }

        return view('watchlist.index')
            ->with('series', $series)
            ->with('items', $items);
    }

    /**
     * Add a serie to the watchlist
     *
     * @return \Illuminate\Http\Response
     */
    public function add(Request $request, $id)
    {
        Auth::user()->watching()->attach($id);

        return response()->json([
            'status' => 'Added to watchlist'
        ]);
    }

    /**
     * Remove a serie from the watchlist
     *
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, $id)
    {
        Auth::user()->watching()->detach($id);

        return response()->json([
            'status' => 'Removed from watchlist'
        ]);
    }
}
