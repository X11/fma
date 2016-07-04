<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Carbon\Carbon;
use App\Activity;

class LogSuccessfulLogin
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

        Activity::log('account.login', null, [
            'user-agent' => $_SERVER["HTTP_USER_AGENT"],
        ]);
    }
}
