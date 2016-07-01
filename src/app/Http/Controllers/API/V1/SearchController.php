<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Serie;

class SearchController extends Controller
{
    public function serie($query)
    {
        $series = Serie::select('id', 'name', 'imdbid')
                            ->where('name', 'like', '%'.$query.'%')
                            ->orderBy('name')
                            ->paginate(5);

        return response()->json($series);
    }
}
