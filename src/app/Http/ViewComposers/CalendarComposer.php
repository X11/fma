<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\User;

class CalendarComposer
{

    /**
     * @param mixed 
     */
    public function __construct()
    {
        //
    }

    /**
     * undocumented function
     *
     * @return void
     */
    public function compose(View $view)
    {
        // Container settings
        $view->with('overview_container', Auth::check()
                                            ? Auth::user()->settings->calendar_overview
                                            : User::$BASE_SETTINGS['calendar_overview']);

        // Create dates
        $start = request('date') ? Carbon::parse(request('date')) : Carbon::now();
        $stop = $start->copy();
        $start = $start->modify('first day of this month')->modify('first monday of this week');
        $stop = $stop->modify('last day of this month')->modify('sunday');

        $weeks = collect();
        $l = ($stop->weekOfYear - $start->weekOfYear);
        for ($k = 0; $k < $l+1; ++$k) {
            $week = collect();
            for ($i = 0; $i < 7; ++$i) {
                $week->put($start->toDateString(), $start->copy());
                $start->modify('+1 day');
            }
            $weeks->push($week);
        }

        $view->with('weeks', $weeks);

        // Add todays date
        $view->with('today', request('date') ? Carbon::parse(request('date')) : Carbon::now());

        // User watching IDs
        $watching_ids = Auth::check()
                         ? Auth::user()
                                ->watching
                                ->pluck('id')
                                ->toArray()
                         : [];

        $view->with('watching_ids', $watching_ids);
    }
    
}
