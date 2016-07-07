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

    public function discover($query)
    {
        // Remove all special characters
        $query = preg_replace('/[^A-Za-z0-9\-\ \.]/', '', $query);

        // Lowercase the query
        $query = strtolower($query);

        // Check for SERIE S**E** Notation
        if (preg_match('/(.*) S([0-9]{2})E([0-9]{2})/i', $query, $matches) ||
            preg_match('/(.*) S.*([0-9]{1,2}) E.*([0-9]{1,2})/i', $query, $matches)){
            $serieQuery = trim($matches[1]);
            $season = intval($matches[2]);
            $episode = intval($matches[3]);

            $serie = Serie::where('name', 'like', '%'.$serieQuery.'%')->first();

            if (!$serie){
                return response()->json([], 404);
            }

            $episode = $serie->episodes()->where([
                                ['episodeSeason', $season],
                                ['episodeNumber', $episode],
                            ])->first();

            if (!$episode){
                return response()->json([], 404);
            }

            return response()->json($episode);

        // Check for IMDB Id
        } elseif (preg_match('/(tt[0-9]{4,})/', $query, $matches)){

            $serie = Serie::where('imdbid', $matches[1])->first();

            if (!$serie){
                return response()->json([], 404);
            }
            return response()->json($serie);

        // Else just check for the serie in the database
        } else {
            $serie = Serie::where('name', 'like', '%'.$query.'%')->first();

            if (!$serie){
                return response()->json([], 404);
            }
            return response()->json($serie);
        }
    }
    
}
