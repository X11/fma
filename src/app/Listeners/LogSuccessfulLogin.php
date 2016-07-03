<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Carbon\Carbon;
use App\Activity;

class LogSuccessfulLogin implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param Login $event
     */
    public function handle(Login $event)
    {
        $event->user->last_login = Carbon::now();
        $event->user->save();

        Activity::log('account.login', []);
    }
}
