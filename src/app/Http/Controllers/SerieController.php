<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Serie;
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
        $limit = 12;
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
            /*
            $tvdbResults = $tvdbResults->getData()->filter(function($value) use ($series_tvdbids){
                return !in_array($value->getId(), $series_tvdbids) && 
                    $value->getSeriesName() != "** 403: Series Not Permitted **" ? 1 : 0 &&
                    Carbon::parse($value->firstAired)->year > 1999;
            });
             */

        } else {
            $series = Serie::orderBy('name')->paginate($limit);
        }

        $series->appends(['q' => $request->input('q')]);    

        return view('serie.index')
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
            $tvshowPoster = $client->series()->getImagesWithQuery($this->serie->tvdbid, [
                'keyType' => 'poster'
            ])->getData()->sortByDesc(function($a){
                return $a->getRatingsInfo()["average"];
            })->first()->getFileName();
        } catch (\Exception $e){
            dd($e);
            return back()->with('status', 'Bad tvdbid');
        }

        $show = Serie::firstOrNew(['tvdbid' => $request->input('tvdbid')]);
        $show->name = $tvshow->getSeriesName();
        $show->overview = $tvshow->getOverview();
        $show->tvdbid = $request->input('tvdbid');
        $show->imdbid = $tvshow->getImdbId();
        $show->poster = $tvshowPoster;
        $show->rating = $tvshow->getSiteRating();
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
