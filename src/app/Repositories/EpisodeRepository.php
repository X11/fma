<?php

namespace App\Repositories;

use App\User;
use Carbon\Carbon;
use App\Episode;
use Auth;

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
            ->where('episodeSeason', '>', '0')
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

    /**
     * undocumented function
     *
     * @return Collection
     */
    public function getEpisodesMetaDataBetween($start, $stop)
    {
        $user = Auth::user();
        if ($user){
            $serie_ids = $user->watching()
                                ->get()
                                ->pluck('id')
                                ->toArray();
        } else {
            $serie_ids = [];
        }

        $days = Episode::whereBetween('aired', [
                $start->toDateString(),
                $stop->toDateString(),
            ])
            ->where('episodeSeason', '>', '0')
            ->with('serie')
            ->get()
            ->sortBy('aired')
            ->groupBy('air_date');

        $meta = collect();

        foreach ($days as $day => $episodes) {
            $metaDay = [
                'tracking' => 0,
                'premiers' => 0,
                'returning' => 0,
                'season_finale' => 0,
                'series' => 0,
                'episodes' => collect(),
            ];
            foreach ($episodes as $episode) {
                $metaDay['series']++;
                if (in_array($episode->serie->id, $serie_ids)) $metaDay['tracking']++;
                if ($episode->serie_premier) $metaDay['premiers']++;
                elseif ($episode->season_Premier) $metaDay['returning']++;
                elseif ($episode->season_finale) $metaDay['season_finale']++;
                $metaDay['episodes']->push($episode);
            }
            $meta->put($day, $metaDay);
        }

        return $meta;
    }
    
}
