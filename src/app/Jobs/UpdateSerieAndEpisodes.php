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
use App\Media;
use App\Person;

class UpdateSerieAndEpisodes extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $serie;
    protected $client;
    protected $serieExtension;

    /**
     * Create a new job instance.
     */
    public function __construct(\App\Serie $serie)
    {
        $this->serie = $serie;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $client = App::make('tvdb');

        $token = Cache::get('tvdb_token', function () use ($client) {
            $t = $client->authentication()->login(env('TVDB_KEY'), null, null);
            Cache::put('tvdb_token', $t, 1200);

            return $t;
        }, 1200);

        $client->setToken($token);

        $this->client = $client;
        $serieExtension = $client->series();
        $this->serieExtension = $serieExtension;

        if ($this->serie->updated_at < $serieExtension->getLastModified($this->serie->tvdbid) || !$this->serie->status){
            // Get serie from TVDB
            $serie = $serieExtension->get($this->serie->tvdbid);

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
            $this->serie->status = $serie->getStatus();
            $this->serie->network = $serie->getNetwork();
            $this->serie->airtime = $serie->getAirsTime();
            $this->serie->airday = $serie->getAirsDayOfWeek();
            $this->serie->runtime = $serie->getRuntime();

            // Try getting the poster image
            $this->getPoster();
            $this->getFanart();

            $episodeIds = $this->getEpisodes();
            \DB::table('episodes')->where('serie_id', $this->serie->id)->whereNotIn('episodeid', $episodeIds)->delete();

            $this->getActors();
        }

        // Do we have an TMDB ID?
        if (!$this->serie->tmdbid){
            $this->getTMDBID();
        }

        $this->getMedia();

        // Save serie
        $this->serie->touch();
        $this->serie->save();

    }

    public function getFanart()
    {
        try {
            $serieFanart = $this->client->series()
                                    ->getImagesWithQuery($this->serie->tvdbid, [
                                        'keyType' => 'fanart',
                                    ])
                                    ->getData()
                                    ->sortByDesc(function ($a) {
                                        return $a->getRatingsInfo()['average'];
                                    })
                                    ->first()
                                    ->getFileName();

            $this->serie->fanart = $serieFanart;
        } catch (\Exception $e) {
            $serieFanart = null;
        }
    }

    public function getPoster()
    {
        try {
            $seriePoster = $this->client->series()
                                    ->getImagesWithQuery($this->serie->tvdbid, [
                                        'keyType' => 'poster',
                                    ])
                                    ->getData()
                                    ->sortByDesc(function ($a) {
                                        return $a->getRatingsInfo()['average'];
                                    })
                                    ->first()
                                    ->getFileName();

            $this->serie->poster = $seriePoster;
        } catch (\Exception $e) {
            $seriePoster = null;
        }
    }
    

    public function getEpisodes()
    {
        // Get episodes
        $episodes = [];
        $episodeIds = [];
        $page = 1;
        do {
            try {
                $serieEpisodes = $this->serieExtension->getEpisodes($this->serie->tvdbid, $page);
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

        return $episodeIds;
    }
    

    public function getActors()
    {
        try {
            $actorIds = [];
            $actors = $this->serieExtension->getActors($this->serie->tvdbid);

            foreach($actors->getData() as $actor){
                if ($actor->getName() != ""){
                    $person = Person::firstOrNew([
                        'name' => $actor->getName()
                    ]);
                    $person->name = $actor->getName();
                    $person->save();

                    $actorIds[$person->id] = [
                        'sort' => $actor->getSortOrder(),
                        'role' => $actor->getRole(),
                        'image' => $actor->getImage(),
                    ];
                }
            }

            $this->serie->cast()->sync($actorIds);
        } catch (\Exception $e) { }
    }
    

    public function getTMDBID()
    {
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
    

    public function getMedia()
    {
        $medias = [];
        if ($this->serie->tmdbid){
            try {
                $res = Guzzle::request('GET', 'http://api.themoviedb.org/3/tv/' . $this->serie->tmdbid. '/videos?api_key=' . env('TMDB_KEY'), [
                    'Accept' => 'application/json'
                ]);

                $body = json_decode($res->getBody());
                foreach($body->results as $result){
                    if ($result->site == "YouTube"){
                        $media = Media::firstOrNew([
                            'source' => $result->key
                        ]);
                        $medias[] = $media;
                        $media->type = 'youtube';
                        $media->name = $result->name;
                    }
                }
            } catch (\Exception $e){ \Log::debug($e);}
        }
        $this->serie->media()->saveMany($medias);
    }
    
}
