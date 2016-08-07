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
        $start = Carbon::parse('monday a week ago');

        $dates = collect();
        for ($k = 0; $k < 4; ++$k) {
            $week = collect();
            for ($i = 0; $i < 7; ++$i) {
                $week->put($start->toDateString(), ($i * 4) + ($k + 1));
                $start->modify('+1 day');
            }
            $dates->push($week);
        }

        $view->with('dates', $dates);


        // Add todays date
        $view->with('today', Carbon::now()->toDateString());



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
