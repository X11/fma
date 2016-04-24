<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Serie;
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
        $timestring = ($request->input('q')) ? $request->input('q') : '7 days ago';

        $series = Serie::where([
            ['updated_at', '<', Carbon::parse($timestring)->toDateTimeString()],
        ])->orWhere('updated_at', null)->get();

        return view('admin.update.index')
            ->with('series', $series)
            ->with('timestring', $timestring)
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
    public function update(Request $request)
    {
        $series = Serie::find($request->input('seriesid'));
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
}
