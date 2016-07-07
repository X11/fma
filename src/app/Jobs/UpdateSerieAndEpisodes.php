<?php

namespace App\Jobs;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Episode;
use App\Serie;
use App;
use DB;
use Cache;
use Guzzle;

class UpdateSerieAndEpisodes extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $serie;

    /**
     * Create a new job instance.
     */
    public function __construct(Serie $serie)
    {
        $this->serie = $serie;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $client = App::make('tvdb');

        // Get serie from TVDB
        $serieExtension = $client->series();
        $serie = $serieExtension->get($this->serie->tvdbid);

        // Try getting the poster image
        try {
            $seriePoster = $client->series()
                                    ->getImagesWithQuery($this->serie->tvdbid, [
                                        'keyType' => 'poster',
                                    ])
                                    ->getData()
                                    ->sortByDesc(function ($a) {
                                        return $a->getRatingsInfo()['average'];
                                    })
                                    ->first()
                                    ->getFileName();
        } catch (\Exception $e) {
            $seriePoster = null;
        }

        // Try getting the serie fanart
        try {
            $serieFanart = $client->series()
                                    ->getImagesWithQuery($this->serie->tvdbid, [
                                        'keyType' => 'fanart',
                                    ])
                                    ->getData()
                                    ->sortByDesc(function ($a) {
                                        return $a->getRatingsInfo()['average'];
                                    })
                                    ->first()
                                    ->getFileName();
        } catch (\Exception $e) {
            $serieFanart = null;
        }

        // Get genre lookup
        $genre_lookup = Cache::get('genre_lookup', function () {
            $lookup = [];
            $genres = DB::table('genres')
                                ->select('id', 'name')
                                ->get();

            foreach ($genres as $genre) {
                $lookup[$genre->name] = $genre->id;
            }
            Cache::put('genre_lookup', $lookup, 600);
            return $lookup;
        });

        // Sync genres
        $genre_ids = [];
        foreach ($serie->getGenre() as $genre) {
            if (isset($genre_lookup[$genre])) {
                $genre_ids[] = $genre_lookup[$genre];
            }
        }
        $this->serie->genres()->sync($genre_ids);

        // Set general information
        $this->serie->overview = $serie->getOverview();
        $this->serie->imdbid = $serie->getImdbId();
        $this->serie->rating = $serie->getSiteRating();
        $this->serie->poster = $seriePoster;
        $this->serie->fanart = $serieFanart;
        $this->serie->status = $serie->getStatus();
        $this->serie->network = $serie->getNetwork();
        $this->serie->airtime = $serie->getAirsTime();
        $this->serie->airday = $serie->getAirsDayOfWeek();
        $this->serie->runtime = $serie->getRuntime();

        // Get episodes
        $episodes = [];
        $episodeIds = [];
        $page = 1;
        do {
            try {
                $serieEpisodes = $serieExtension->getEpisodes($this->serie->tvdbid, $page);
            } catch (\Exception $e) {
                break;
            }

            foreach ($serieEpisodes->getData() as $episode) {
                $episodeIds[] = $episode->getId();
                $e = Episode::firstOrNew(['episodeid' => $episode->getId()]);
                $episodes[] = $e;
                $e->name = $episode->getEpisodeName();
                $e->overview = $episode->getOverview();
                $e->aired = $episode->getFirstAired();
                $e->episodeNumber = $episode->getAiredEpisodeNumber();
                $e->episodeSeason = $episode->getAiredSeason();
                $e->episodeid = $episode->getId();
            }
        } while ($page = $serieEpisodes->getLinks()->getNext());

        $this->serie->episodes()->saveMany($episodes);

        // Do we have an TMDB ID?
        if (!$this->serie->tmdbid){
            try {
                $res = Guzzle::request('GET', 'http://api.themoviedb.org/3/find/' . $this->serie->tvdbid. '?external_source=tvdb_id&api_key=' . env('TMDB_KEY'),
                    [
                        'Accept' => 'application/json'
                    ]);

                $body = json_decode($res->getBody());
                $data = $body->tv_results[0];
                $this->serie->tmdbid = $data->id;
            } catch (\Exception $e){ }
        }

        // Save serie
        $this->serie->touch();
        $this->serie->save();

        \DB::table('episodes')->where('serie_id', $this->serie->id)->whereNotIn('episodeid', $episodeIds)->delete();
    }
}
