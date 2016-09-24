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
        $start = Carbon::parse('first day of this month')->modify('first monday of this week');
        $stop = Carbon::parse('last day of this month')->modify('sunday');

        $meta = $this->episodes->getEpisodesMetaDataBetween($start, $stop);

        return view('calendar.index')
            ->with('meta', $meta)
            ->with('breadcrumbs', [[
                'name' => 'Calendar',
                'url' => action('CalendarController@index'),
            ]]);
    }
}
