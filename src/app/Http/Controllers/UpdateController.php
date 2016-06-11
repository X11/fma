<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Serie;
use App\Episode;
use App\User;
use Carbon\Carbon;
use App\Jobs\UpdateSerieAndEpisodes;

class UpdateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        return view('admin.update.index')
            ->with('serieCount', Serie::count())
            ->with('episodeCount', Episode::count())
            ->with('userCount', User::count())
            ->with('breadcrumbs', [[
                'name' => "Admin",
                'url' => '/admin'
            ], [
                'name' => "Update",
                'url' => action("UpdateController@index")
            ]]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateSeries(Request $request)
    {
        $series = Serie::where([
            ['updated_at', '<', Carbon::parse($request->input('q'))->toDateTimeString()],
        ])->orWhere('updated_at', null)->get();

        if (!$series)
            return back()
                ->with('status', "No series selected");

        foreach ($series as $serie) {
            dispatch(new UpdateSerieAndEpisodes($serie));
        }
        $count = $series->count();
        return back()
            ->with('status', "$count series updating");
    }

    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateCache(Request $request)
    {

        \Artisan::call('cache:clear');

        return back()
            ->with('status', "Cache cleared");
    }
}
