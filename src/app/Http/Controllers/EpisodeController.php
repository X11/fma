<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Episode;
use App\Serie;
use Auth;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Activity;
use App\Jobs\UpdateEpisode;
use App\Repositories\SourcesRepository;

class EpisodeController extends Controller
{
    private $sources;

    /**
     * Create a new controller instance.
     */
    public function __construct(SourcesRepository $sources)
    {
        $this->middleware('admin', ['only' => ['destroy']]);

        $this->sources = $sources;
    }

    /**
     * Display the specified resource.
     *
     * @param Serie   $serie
     * @param Episode $episode
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Serie $serie, Episode $episode)
    {
        //$serie = Serie::findOrFail($serieId);
        //$episode = $serie->episodes()->findOrFail($episodeId);
        //@todo redirect to right url
        if ($serie->id != $episode->serie_id) {
            throw new NotFoundHttpException();
        }

        // Is token valid?
        $validToken = false;
        if ($request->has('_t')) {
            $baseToken = $request->input('_t');
            $testToken = base64_decode($baseToken);
            $validToken = ($testToken + 3600) > time();
        }

        $episode->load('guests', 'writers', 'directors');

        return view('episode.show')
            ->with('refresh', false)
            ->with('episode', $episode)
            ->with('validToken', $validToken)
            ->with('prevEpisode', $episode->prev())
            ->with('nextEpisode', $episode->next())
            ->with('serie', $serie)
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

    public function sources(Request $request, Episode $episode)
    {
        // Is token valid?
        $validToken = false;
        if ($request->has('_t')) {
            $baseToken = $request->input('_t');
            $testToken = base64_decode($baseToken);
            $validToken = ($testToken + 3600) > time();
        }

        $search_query = preg_replace('/\([0-9]+\)/', '', $episode->serie->name).' '.$episode->season_episode;

        // Check for a valid user or a valid share token
        if ((Auth::check() && Auth::user()->isMember()) || $validToken) {

            // Get magnets from the cache else populate the cache 
            $magnets = $this->sources->searchMagnets($search_query, function ($magnet) use ($episode) {
                return preg_match("/$episode->season_episode/", $magnet->getName());
            });

            // Get links from cache
            $links = $this->sources->searchLinks(strtolower($episode->serie->name), $episode->episodeSeason, $episode->episodeNumber);
        } else {
            abort(403);
        }

        return response()->json([
            'links' => $links,
            'magnets' => $magnets
        ], 200);
    }

    public function update(Request $request, Episode $episode)
    {
        if (!Auth::user()->isModerator()) {
            if ($episode->updated_at->diffInHours(Carbon::now()) < 24) {
                return redirect()->back()->with('status', 'Serie already updated in the last 24 hous');
            }
        }

        dispatch(new UpdateEpisode($episode));

        Activity::log('episode.update', $episode->id);

        return back();
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

        Activity::log('episode.watched', $episodeId);

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
