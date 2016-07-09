<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Serie;
use App\Genre;
use App\Jobs\FetchSerieEpisodes;
use App\Jobs\UpdateSerieAndEpisodes;
use App;
use App\Repositories\SerieRepository;
use App\Activity;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class SerieController extends Controller
{
    private $series;

    /**
     * Create a new controller instance.
     */
    public function __construct(SerieRepository $series)
    {
        $this->series = $series;

        $this->middleware('admin', ['only' => 'destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = 18;
        $tvdbResults = null;

        if ($request->input('q')) {
            $series = $this->series->search($request->input('q'), 10);

            $series_tvdbids = Serie::select('tvdbid')->get()->pluck('tvdbid')->toArray();
            $client = App::make('tvdb');
            try {
                $tvdbResults = $client->search()->seriesByName($request->input('q'));
                $tvdbResults = $tvdbResults->getData();
            } catch (\Exception $e) {
                $tvdbResults = collect();
            }
            $tvdbResults = $tvdbResults->filter(function ($value) use ($series_tvdbids) {
                if ($value->getFirstAired() != '' && intval(substr($value->getFirstAired(), 0, 4)) < 2000) {
                    return false;
                }
                if (in_array($value->getId(), $series_tvdbids)) {
                    return false;
                }
                if (substr($value->getSeriesName(), 0, 2) == '**') {
                    return false;
                }
                if (stripos($value->getSeriesName(), 'JAPANESE') !== false) {
                    return false;
                }
                if ($value->getStatus() == '') {
                    return false;
                }
                if ($value->getNetwork() == '') {
                    return false;
                }

                return true;
            })->sortByDesc(function ($value) {
                $add = ($value->getBanner() != '' ? 10000 : 0);
                if (preg_match('/\(([\d]{4})\)$/', $value->getSeriesName(), $matches)) {
                    $add += intval($matches[1].'0');
                }

                return $add + $value->getId();
            });
        } elseif ($request->input('_genre')) {
            $genre = Genre::findOrFail($request->input('_genre'));

            $series = $this->series->allFromGenre($genre, $limit);
        } elseif ($request->input('_sort')) {
            switch ($request->input('_sort')) {
                case 'name':
                    $series = $this->series->sortedByName($limit);
                    break;
                case 'rating':
                    $series = $this->series->sortedByRating($limit);
                    break;
                case 'recent':
                    $series = $this->series->sortedByRecent($limit);
                    break;
                case 'watched':
                    $series = $this->series->sortedByWatched($limit);
                    break;
                default:
                    return redirect()->action('SerieController@index');
            }
        } else {
            $series = $this->series->sortedByBest($limit);
        }

        $series->appends(['q' => $request->input('q')]);

        return view('serie.index')
            ->with('genres', Genre::has('series')->get())
            ->with('query', $request->input('q'))
            ->with('_sort', $request->input('_sort'))
            ->with('_genre', $request->input('_genre'))
            ->with('series', $series)
            ->with('tvdbResults', $tvdbResults)
            ->with('breadcrumbs', [[
                'name' => 'Series',
                'url' => action('SerieController@index'),
            ]]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'tvdbid' => 'required|numeric',
        ]);

        $show = Serie::where('tvdbid', $request->input('tvdbid'))->first();

        if ($show) {
            return redirect()->action('SerieController@show', ['id' => $show->id]);
        }

        // Move this in a job so it doesn't block the request

        $client = App::make('tvdb');

        try {
            $tvshow = $client->series()->get($request->input('tvdbid'));
        } catch (\Exception $e) {
            return back()->with('status', 'Bad tvdbid');
        }

        try {
            $tvshowPoster = $client->series()->getImagesWithQuery($request->input('tvdbid'), [
                'keyType' => 'poster',
            ])->getData()->sortByDesc(function ($a) {
                return $a->getRatingsInfo()['average'];
            })->first()->getFileName();
        } catch (\Exception $e) {
            $tvshowPoster = null;
        }

        try {
            $tvshowFanart = $client->series()->getImagesWithQuery($request->input('tvdbid'), [
                'keyType' => 'fanart',
            ])->getData()->sortByDesc(function ($a) {
                return $a->getRatingsInfo()['average'];
            })->first()->getFileName();
        } catch (\Exception $e) {
            $tvshowFanart = null;
        }

        $show = Serie::firstOrNew(['tvdbid' => $request->input('tvdbid')]);
        $show->name = $tvshow->getSeriesName();
        $show->overview = $tvshow->getOverview();
        $show->tvdbid = $request->input('tvdbid');
        $show->imdbid = $tvshow->getImdbId();
        $show->poster = $tvshowPoster;
        $show->fanart = $tvshowFanart;
        $show->rating = $tvshow->getSiteRating();
        $show->status = $tvshow->getStatus();
        $show->network = $tvshow->getNetwork();
        $show->airtime = $tvshow->getAirsTime();
        $show->airday = $tvshow->getAirsDayOfWeek();
        $show->runtime = $tvshow->getRuntime();
        $show->save();

        dispatch(new FetchSerieEpisodes($show));

        Activity::log('serie.add', $show->id);

        return redirect()
            ->action('SerieController@show', ['id' => $show->id])
            ->with('refresh', true)
            ->with('status', 'Serie is fetching the episodes, auto refreshing in a few seconds..');
    }

    /**
     * Display the specified resource.
     *
     * @param Serie $serie
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Serie $serie)
    {
        $seasons = $serie->episodes()
                            ->with('serie')
                            ->get()
                            ->groupBy('episodeSeason')
                            ->sortBy('episodeNumber');

        return view('serie.show')
            ->with('serie', $serie->load('media'))
            ->with('seasons_numbers', $seasons->keys()->sort())
            ->with('seasons', $seasons)
            ->with('breadcrumbs', [[
                'name' => 'Series',
                'url' => action('SerieController@index'),
            ], [
                'name' => $serie->name,
                'url' => url($serie->url),
            ]]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $serie = Serie::findOrFail($id);

        if (!Auth::user()->isModerator()){
            if ($serie->updated_at->diffInHours(Carbon::now()) < 24){
                return redirect()->back()->with('status', 'Serie already updated in the last 24 hous');
            }
        }
        
        dispatch(new UpdateSerieAndEpisodes($serie));

        Activity::log('serie.update', $serie->id);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Serie $serie)
    {
        $serie->delete();

        Activity::log('serie.remove', $serie->id);

        return redirect('/serie');
    }
}
