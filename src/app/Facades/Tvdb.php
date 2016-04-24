<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Tvdb extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'tvdb';
    }
}
