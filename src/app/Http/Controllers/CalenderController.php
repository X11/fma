<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use App\Episode;
use Carbon\Carbon;

class CalenderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $start_date = Carbon::parse("monday a week ago");
        $diffDays = $start_date->diffInDays(Carbon::now());

        $dates = collect();
        for ($i = -$diffDays; $i <= 34-$diffDays; $i++) {
            $dates->put(Carbon::parse("$i days")->toDateString(), []);
        }

        $episodes = Episode::whereBetween('aired', [
                $start_date->toDateString(), 
                Carbon::now()->addDays(35-$diffDays)->toDateString()
            ])
            ->with('serie')
            ->get()
            ->sortBy('aired')
            ->groupBy('air_date');


        $watching_ids = (Auth::user()) ? Auth::user()
            ->watching
            ->pluck('id')
            ->toArray() : [];

        $today = Carbon::now()->toDateString();

        return view('calender.index')
            ->with('today', $today)
            ->with('episode_chunks', collect($dates)->merge($episodes)->chunk(7))
            ->with('watching_ids', $watching_ids)
            ->with('breadcrumbs', [[
                'name' => "Calender",
                'url' => action("CalenderController@index")
            ]]);
    }
}
