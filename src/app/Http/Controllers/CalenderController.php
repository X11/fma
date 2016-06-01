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
        $diffDays = $start_date->diffInDays(Carbon::now())%7;

        $dates = collect();
        for ($i = 0; $i < 7; $i++) {
            $weeks = collect();

            $day = clone $start_date;
            for ($k = 0; $k < 5; $k++) {
                $weeks->push($day->toDateString());
                $day->modify('+1 week');
            }

            $dates->put($i, $weeks);
            $start_date->modify('+1 day');
        }

        $episodes = Episode::whereBetween('aired', [
                $start_date->toDateString(), 
                Carbon::now()->addDays(28-(7-$diffDays))->toDateString()
            ])
            ->with('serie')
            ->get()
            ->sortBy('aired')
            ->groupBy('air_date');


        $watching_ids = (Auth::user()) ? Auth::user()
            ->watching
            ->pluck('id')
            ->toArray() : [];

        return view('calender.index')
            ->with('today', Carbon::now()->toDateString())
            ->with('dates', $dates)
            ->with('episodes', $episodes)
            ->with('watching_ids', $watching_ids)
            ->with('breadcrumbs', [[
                'name' => "Calender",
                'url' => action("CalenderController@index")
            ]]);
    }
}
