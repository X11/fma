<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Episode;
use TorrentSearch\TorrentSearch;
use App\Serie;
use Auth;
use App\Jobs\DownloadEpisodeFile;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EpisodeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin', ['only' => ['destroy']]);
    }

    /**
     * Display the specified resource.
     *
     * @param  Serie $serie
     * @param  Episode $episode
     * @return \Illuminate\Http\Response
     */
    public function show($serie, $episode)
    {
        //$serie = Serie::findOrFail($serieId);
        //$episode = $serie->episodes()->findOrFail($episodeId);
        //@todo redirect to right url
        if ($serie->id != $episode->serie->id) throw new NotFoundHttpException;

        $magnets = [];
        $search_query = preg_replace('/\([0-9]+\)/', '', $serie->name) . ' ' . $episode->season_episode;

        if (Auth::user()->isMember()){
            $ts = new TorrentSearch();
            $magnets = $ts->search(strtolower($search_query), '1');
            $magnets = array_filter($magnets, function($magnet) use ($episode) {
                return preg_match("/$episode->season_episode/", $magnet->getName());
            });
            $magnets = array_filter($magnets, function($magnet) use ($episode) {
                return preg_match("/\[(ettv|rartv)\]/", $magnet->getName());
            });
        }

        $nextEpisode = Episode::where([ ['serie_id', $serie->id],
                                        ['episodeSeason', $episode->episodeSeason],
                                        ['episodeNumber', $episode->episodeNumber+1]
                                    ])->orWhere([['serie_id', $serie->id],
                                                ['episodeSeason', $episode->episodeSeason+1],
                                                ['episodeNumber', 1] 
                                            ])->first();
        
        $prevEpisode = Episode::where([ ['serie_id', $serie->id],
                                        ['episodeSeason', $episode->episodeSeason],
                                        ['episodeNumber', $episode->episodeNumber-1]
                                    ])->first()
                                ?: Episode::where([ ['serie_id', $serie->id],
                                                    ['episodeSeason', $episode->episodeSeason-1]
                                                ])->orderBy('episodeNumber', 'desc')->first();

        return view('episode.show')
            ->with('episode', $episode)
            ->with('nextEpisode', $nextEpisode)
            ->with('prevEpisode', $prevEpisode)
            ->with('serie', $serie)
            ->with('magnets', $magnets)
            ->with('search_query', $search_query)
            ->with('breadcrumbs', [[
                'name' => "Series",
                'url' => action("SerieController@index")
            ], [
                'name' => $serie->name,
                'url' => url($serie->url) . '#seasons/' . $episode->episodeSeason
            ], [
                'name' => $episode->season_episode,
                'url' => url($episode->url)
            ]]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Episode $episode)
    {
        $episode->delete();

        return redirect()->action('SerieController@show', ['id' => $episode->serie->id]);
    }

    /**
     * Add an episode to the watched list
     *
     * @return \Illuminate\Http\Response
     */
    public function markWatched(Request $request, $episodeId)
    {
        Auth::user()->watched()->attach($episodeId);

        return response()->json([
            'status' => 'Marked as watched'
        ]);
    }

    /**
     * remove an episode from the watched list
     *
     * @return \Illuminate\Http\Response
     */
    public function unmarkWatched(Request $request, $episodeId)
    {
        Auth::user()->watched()->detach($episodeId);

        return response()->json([
            'status' => 'Unmarked as watched'
        ]);
    }
}
