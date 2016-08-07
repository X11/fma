<?php

namespace App\Repositories;

use App\Serie;
use App\Genre;
use Auth;

class SerieRepository
{
    /**
     * undocumented function.
     */
    public function search($query, $limit)
    {
        return Serie::where('name', 'like', '%'.$query.'%')
                        ->orderBy('name')
                        ->paginate($limit);
    }
    

    /**
     * undocumented function.
     */
    public function allFromGenre(Genre $genre, $limit)
    {
        $series = $genre->series()
                            ->orderBy('name')
                            ->paginate($limit);

        $series->appends(['_genre' => $genre->id]);

        return $series;
    }

    /**
     * undocumented function.
     */
    public function sortedByBest($limit)
    {
        return Serie::orderBy('rating', 'desc')
                        ->orderBy('tvdbid', 'desc')
                        ->paginate($limit);
    }

    /**
     * undocumented function.
     */
    public function sortedByName($limit)
    {
        return Serie::orderBy('name', 'asc')
                        ->paginate($limit);
    }

    /**
     * undocumented function.
     */
    public function sortedByRating($limit)
    {
        return Serie::orderBy('rating', 'desc')
                        ->orderBy('name', 'asc')
                        ->paginate($limit);
    }

    /**
     * undocumented function.
     */
    public function sortedByRecent($limit)
    {
        return Serie::orderBy('created_at', 'desc')
                        ->orderBy('name', 'asc')
                        ->paginate($limit);
    }

    /**
     * Return serie sorted by the given input
     *
     * @return void
     */
    public function sortedFromInput($input, $limit)
    {
        switch ($input) {
            case 'name':
                return $this->series->sortedByName($limit);
            case 'rating':
                return $this->series->sortedByRating($limit);
            case 'recent':
                return $this->series->sortedByRecent($limit);
            case 'watched':
                return $this->series->sortedByWatched($limit);
            default:
                return null;
        }
    }
    

    /**
     * undocumented function.
     */
    public function sortedByWatched($limit)
    {
        $serieIds = Auth::user()->watched()
                                ->withPivot('id')
                                ->orderBy('episodes_watched.id', 'desc')
                                ->get()
                                ->unique('serie_id')
                                ->pluck('serie_id')
                                ->toArray();

        return Serie::whereIn('id', $serieIds)
                        ->orderByRaw('FIND_IN_SET(id, ?)', [implode(',', $serieIds)])
                        ->paginate($limit);
    }
}
