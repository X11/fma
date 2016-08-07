<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Serie;
use App\Genre;
use App\Jobs\FetchSerieEpisodes;
use App\Jobs\UpdateSerieAndEpisodes;
use App;
use App\Repositories\SerieRepository;
use App\Repositories\TvdbRepository;
use App\Activity;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class SerieController extends Controller
{
    private $series;
    private $tvdb;

    /**
     * Create a new controller instance.
     */
    public function __construct(SerieRepository $series, TvdbRepository $tvdb)
    {
        $this->series = $series;
        $this->tvdb = $tvdb;

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
            $series = $this->series->search($request->input('q'), $limit);
            $tvdbResults = $this->tvdb->search($request->input('q'));
        } elseif ($request->input('_genre')) {
            $genre = Genre::findOrFail($request->input('_genre'));
            $series = $this->series->allFromGenre($genre, $limit);
        } elseif ($request->input('_sort')) {
            $series = $this->series->sortedFromInput($request->input('_sort'), $limit);
        } else {
            $series = $this->series->sortedByBest($limit);
        }

        $series->appends(['q' => $request->input('q')]);

        return view('serie.index')
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
            return redirect()->action('SerieController@show', ['id' => $show->slug]);
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
        $show->slug = str_slug($show->name);
        $show->overview = $tvshow->getOverview();
        $show->tvdbid = $request->input('tvdbid');
        $show->imdbid = $tvshow->getImdbId();
        $show->poster = $tvshowPoster;
        $show->fanart = $tvshowFanart;
        $show->rating = $tvshow->getSiteRating();
        $show->network = $tvshow->getNetwork();
        $show->airtime = $tvshow->getAirsTime();
        $show->airday = $tvshow->getAirsDayOfWeek();
        $show->runtime = $tvshow->getRuntime();
        $show->save();

        //dispatch(new FetchSerieEpisodes($show));
        dispatch(new UpdateSerieAndEpisodes($show));

        Activity::log('serie.add', $show->id);

        return redirect()
            ->action('SerieController@show', ['id' => $show->slug])
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
    public function show(Request $request, Serie $serie)
    {
        $seasons = $serie->episodes()
                            ->with('serie')
                            ->get()
                            ->groupBy('episodeSeason')
                            ->sortBy('episodeNumber');

        $more = $request->get('more');

        $serie->load([
            'media',
            'cast' => function($query) use ($more){
                return $query
                    ->when(!$more, function($query){
                        return $query->whereIn('serie_cast.sort', [0, 1,2]);
                    })
                    ->orderBy('serie_cast.sort', 'asc')
                    ->orderBy('name', 'asc');
            }
        ]);

        return view('serie.show')
                ->with('more', $more)
                ->with('serie', $serie)
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
