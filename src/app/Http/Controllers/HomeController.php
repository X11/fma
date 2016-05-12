<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Serie;
use App\Jobs\FetchSerieEpisodes;
use Carbon\Carbon;
use App\Episode;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $serie_ids = Auth::user()
            ->watching()
            ->get()
            ->pluck('id');

        $dates = collect();
        for ($i = -7; $i <= 0; $i++) {
            $dates->put(Carbon::parse("$i days")->toDateString(), []);
        }

        $episodes = Episode::whereIn('serie_id', $serie_ids)
            ->whereBetween('aired', [ 
                Carbon::parse('7 days ago')->toDateTimeString(), 
                Carbon::parse('today')->toDateTimeString()
            ])
            ->with('serie')
            ->get()
            ->groupBy('air_date');

        /*
        $episodes = Episode::where([
            ['aired', '>', Carbon::parse('7 days ago')->toDateTimeString()],
            ['aired', '<', Carbon::parse('today')->toDateTimeString()],
        ])->get();
        */

        $days = $dates->merge($episodes);

        return view('home')
            ->with('days', $days->reverse())
            ->with('breadcrumbs', [[
                'name' => "Home",
                'url' => action("HomeController@index")
            ]]);
    }

    public function seed()
    {
        $series = Serie::all();
        foreach ($series as $serie) {
            $this->dispatch(new FetchSerieEpisodes($serie));
        }
        return redirect('/home');
    }
}
