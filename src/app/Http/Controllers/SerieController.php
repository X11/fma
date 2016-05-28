<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Serie;
use App\Genre;
use App\Jobs\FetchSerieEpisodes;
use App\Jobs\UpdateSerieAndEpisodes;
use Carbon\Carbon;
use App;

class SerieController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin', ['except' => ['index', 'show', 'store']]);
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

        if ($request->input('q')){
            $series = Serie::where('name', 'like', '%' . $request->input('q') . '%')->orderBy('name')->paginate($limit);

            $series_tvdbids = $series->pluck('tvdbid')->toArray();

            $client = App::make('tvdb');
            try {
                $tvdbResults = $client->search()->seriesByName($request->input('q'));
                $tvdbResults = $tvdbResults->getData();
            } catch (\Exception $e){
                $tvdbResults = [];
            }
            $tvdbResults = $tvdbResults->filter(function($value) use ($series_tvdbids){
                if (Carbon::parse($value->getFirstAired())->year < 2000) return false;
                if (in_array($value->getId(), $series_tvdbids)) return false;
                if (substr($value->getSeriesName(), 0, 2) == '**') return false;
                return true;
            })->sort(function($value){
                return $value->getId();
            });

        } else if ($request->input('_genre')){
                $genre = Genre::findOrFail($request->input('_genre'));
                $series = $genre->series()
                                ->orderBy('name')
                                ->paginate($limit);
                $series->appends(['_genre' => $request->input('_genre')]);    
        } else if ($request->input('_sort')){
            switch ($request->input('_sort')){
                case 'rating':
                    $series = Serie::orderBy('rating', 'desc')
                                    ->orderBy('name', 'asc')
                                    ->paginate($limit);
                    break;
                case 'recent':
                    $series = Serie::orderBy('created_at', 'desc')
                                    ->orderBy('name', 'asc')
                                    ->paginate($limit);
                    break;
                default:
                    return redirect()->action('SerieController@index');
            }
        } else {
            $series = Serie::orderBy('name')->paginate($limit);
        }

        $series->appends(['q' => $request->input('q')]);    

        return view('serie.index')
            ->with('genres', Genre::has('series')->get())
            ->with('query', $request->input('q'))
            ->with('series', $series)
            ->with('tvdbResults', $tvdbResults)
            ->with('breadcrumbs', [[
                'name' => "Series",
                'url' => action("SerieController@index")
            ]]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'tvdbid' => 'required|numeric'
        ]);

        $show = Serie::where('tvdbid', $request->input('tvdbid'))->first();

        if ($show)
            return redirect()->action('SerieController@show', ['id' => $show->id]);

        // Move this in a job so it doesn't block the request

        $client = App::make('tvdb');

        try {
            $tvshow = $client->series()->get($request->input('tvdbid'));
        } catch (\Exception $e){
            return back()->with('status', 'Bad tvdbid');
        }

        try { $tvshowPoster = $client->series()->getImagesWithQuery($request->input('tvdbid'), [
                'keyType' => 'poster'
            ])->getData()->sortByDesc(function($a){
                return $a->getRatingsInfo()["average"];
            })->first()->getFileName(); 
        } catch (\Exception $e) {
            $tvshowPoster = null;
        }

        try { $tvshowFanart = $client->series()->getImagesWithQuery($request->input('tvdbid'), [
                'keyType' => 'fanart'
            ])->getData()->sortByDesc(function($a){
                return $a->getRatingsInfo()["average"];
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
        $show->save();

        dispatch(new FetchSerieEpisodes($show));

        return redirect()
            ->action('SerieController@show', ['id' => $show->id])
            ->with('refresh', true)
            ->with('status', 'Serie is fetching the episodes, auto refreshing in a few seconds..');
    }

    /**
     * Display the specified resource.
     *
     * @param  Serie $serie
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
            ->with('serie', $serie)
            ->with('seasons_numbers', $seasons->keys()->sort())
            ->with('seasons', $seasons)
            ->with('breadcrumbs', [[
                'name' => "Series",
                'url' => action("SerieController@index")
            ], [
                'name' => $serie->name,
                'url' => url($serie->url)
            ]]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $serie = Serie::findOrFail($id);
        dispatch(new UpdateSerieAndEpisodes($serie));
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Serie $serie)
    {
        $serie->delete();

        return redirect("/serie");
    }
}
