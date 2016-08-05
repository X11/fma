<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Episode;
use Carbon\Carbon;
use App\Repositories\EpisodeRepository;

class CalendarController extends Controller
{

    protected $episodes;

    /**
     * @param EpisodeController $episodes
     */
    public function __construct(EpisodeRepository $episodes)
    {
        $this->episodes = $episodes;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $start = Carbon::parse('monday a week ago');
        $stop = clone $start;
        $stop->addDays(28);

        $episodes = $this->episodes->getEpisodesBetween($start, $stop);

        $dates = collect();
        for ($k = 0; $k < 4; ++$k) {
            $week = collect();
            for ($i = 0; $i < 7; ++$i) {
                $week->put($start->toDateString(), ($i * 4) + ($k + 1));
                $start->modify('+1 day');
            }
            $dates->push($week);
        }

        return view('calendar.index')
            ->with('today', Carbon::now()->toDateString())
            ->with('dates', $dates)
            ->with('episodes', $episodes)
            ->with('breadcrumbs', [[
                'name' => 'Calendar',
                'url' => action('CalendarController@index'),
            ]]);
    }
}
