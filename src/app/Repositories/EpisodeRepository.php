<?php

namespace App\Repositories;

use App\User;
use Carbon\Carbon;
use App\Episode;

class EpisodeRepository
{

    /**
     * undocumented function
     *
     * @return Collection
     */
    public function getEpisodesBetween($start, $stop)
    {
        $episodes = Episode::whereBetween('aired', [
                $start->toDateString(),
                $stop->toDateString(),
            ])
            ->with('serie')
            ->get()
            ->sortBy('aired')
            ->groupBy('air_date');

        return $episodes;
    }

    /**
     * undocumented function
     *
     * @return Collection
     */
    public function getEpisodesFromUserBetween(User $user, $start, $stop)
    {
        $serie_ids = $user->watching()
                            ->get()
                            ->pluck('id');

        $episodes = Episode::whereIn('serie_id', $serie_ids)
            ->whereBetween('aired', [
                $start->toDateTimeString(),
                $stop->toDateTimeString(),
            ])
            ->where('episodeSeason', '>', '0')
            ->with('serie')
            ->get()
            ->groupBy('air_date');

        return $episodes;
    }
    
}
