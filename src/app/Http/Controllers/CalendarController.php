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

        return view('calendar.index')
            ->with('episodes', $episodes)
            ->with('breadcrumbs', [[
                'name' => 'Calendar',
                'url' => action('CalendarController@index'),
            ]]);
    }
}
