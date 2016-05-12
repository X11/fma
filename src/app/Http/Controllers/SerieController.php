<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Serie;
use App\Jobs\FetchSerieEpisodes;
use App\Jobs\UpdateSerieAndEpisodes;
use Carbon\Carbon;

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

            $series_imdbids = $series->pluck('tvdbid')->toArray();

            $tvdbResults = \TVDB::searchTvShow($request->input('q'));
            $tvdbResults = array_filter($tvdbResults, function($value) use ($series_imdbids){
                return $value->getBannerUrl() && !in_array($value->getTheTvDbId(), $series_imdbids) && $value->getName() != "** 403: Series Not Permitted **" ? 1 : 0;
            });

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
        $tvshow = \TVDB::getTvShow($request->input('tvdbid'));
        if (!$tvshow)
            return back()->with('status', 'Bad tvdbid');

        $show = Serie::firstOrNew(['tvdbid' => $request->input('tvdbid')]);
        $show->name = $tvshow->getName();
        $show->overview = $tvshow->getOverview();
        $show->tvdbid = $request->input('tvdbid');
        $show->poster = $tvshow->getPosterUrl();
        $show->fanart = $tvshow->getFanartUrl();
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
        $seasons= $serie->episodes()
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
