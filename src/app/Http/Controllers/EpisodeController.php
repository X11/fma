<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Episode;
use TorrentSearch\TorrentSearch;
use Sources\Sources;
use App\Serie;
use Auth;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Activity;

class EpisodeController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('admin', ['only' => ['destroy']]);
    }

    /**
     * Display the specified resource.
     *
     * @param Serie   $serie
     * @param Episode $episode
     *
     * @return \Illuminate\Http\Response
     */
    public function show($serie, $episode)
    {
        //$serie = Serie::findOrFail($serieId);
        //$episode = $serie->episodes()->findOrFail($episodeId);
        //@todo redirect to right url
        if ($serie->id != $episode->serie_id) {
            throw new NotFoundHttpException();
        }

        $links = [];
        $magnets = [];
        $search_query = preg_replace('/\([0-9]+\)/', '', $serie->name).' '.$episode->season_episode;

        if (Auth::user()->isMember()) {
            $ts = new TorrentSearch();
            $magnets = $ts->search(strtolower($search_query), '1');
            $magnets = array_filter($magnets, function ($magnet) use ($episode) {
                return preg_match("/$episode->season_episode/", $magnet->getName());
            });
            $magnets = array_filter($magnets, function ($magnet) use ($episode) {
                return preg_match("/\[(ettv|rartv)\]/", $magnet->getName());
            });

            try {
                $links = ((new Sources())->search(strtolower($serie->name), $episode->episodeSeason, $episode->episodeNumber));
            } catch (\Exception $e) {
                $links = [];
            }
        }

        return view('episode.show')
            ->with('episode', $episode)
            ->with('prevEpisode', $episode->prev())
            ->with('nextEpisode', $episode->next())
            ->with('serie', $serie)
            ->with('magnets', $magnets)
            ->with('links', $links)
            ->with('search_query', $search_query)
            ->with('breadcrumbs', [[
                'name' => 'Series',
                'url' => action('SerieController@index'),
            ], [
                'name' => $serie->name,
                'url' => url($serie->url).'#seasons/'.$episode->episodeSeason,
            ], [
                'name' => $episode->season_episode,
                'url' => url($episode->url),
            ]]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Episode $episode)
    {
        $episode->delete();

        return redirect()->action('SerieController@show', ['id' => $episode->serie->id]);
    }

    /**
     * Add an episode to the watched list.
     *
     * @return \Illuminate\Http\Response
     */
    public function markWatched(Request $request, $episodeId)
    {
        Auth::user()->watched()->attach($episodeId);

        Activity::log('episode.watched', ['episode_id' => $episodeId]);

        return response()->json([
            'status' => 'Marked as watched',
        ]);
    }

    /**
     * remove an episode from the watched list.
     *
     * @return \Illuminate\Http\Response
     */
    public function unmarkWatched(Request $request, $episodeId)
    {
        Auth::user()->watched()->detach($episodeId);

        return response()->json([
            'status' => 'Unmarked as watched',
        ]);
    }
}
