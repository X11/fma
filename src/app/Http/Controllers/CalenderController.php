<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        $start = Carbon::parse('monday a week ago');
        $start_date = $start->toDateString();
        $diffDays = $start->diffInDays(Carbon::now()) % 7;

        $dates = collect();
        for ($k = 0; $k < 4; ++$k) {
            $week = collect();
            for ($i = 0; $i < 7; ++$i) {
                $week->put($start->toDateString(), ($i * 4) + ($k + 1));
                $start->modify('+1 day');
            }
            $dates->push($week);
        }

        $episodes = Episode::whereBetween('aired', [
                $start_date,
                Carbon::now()->addDays(28 - (7 - $diffDays))->toDateString(),
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
                'name' => 'Calender',
                'url' => action('CalenderController@index'),
            ]]);
    }
}
