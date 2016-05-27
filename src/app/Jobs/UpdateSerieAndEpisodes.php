<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Episode;
use App\Serie;
use App;
use DB;
use Cache;

class UpdateSerieAndEpisodes extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $serie;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Serie $serie)
    {
        $this->serie = $serie;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $client = App::make('tvdb');

        $serieExtension = $client->series();

        $serie = $serieExtension->get($this->serie->tvdbid);

        try { $seriePoster = $client->series()->getImagesWithQuery($this->serie->tvdbid, [
                'keyType' => 'poster'
            ])->getData()->sortByDesc(function($a){
                return $a->getRatingsInfo()["average"];
            })->first()->getFileName(); 
        } catch (\Exception $e) {
            $seriePoster = null;
        }

        try { $serieFanart = $client->series()->getImagesWithQuery($this->serie->tvdbid, [
                'keyType' => 'fanart'
            ])->getData()->sortByDesc(function($a){
                return $a->getRatingsInfo()["average"];
            })->first()->getFileName(); 
        } catch (\Exception $e) {
            $serieFanart = null;
        }

        $genre_lookup = Cache::get('genre_lookup', function(){

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

        $genre_ids = [];
        foreach ($serie->getGenre() as $genre) {
            if (isset($genre_lookup[$genre])){
                $genre_ids[] = $genre_lookup[$genre];
            }
        }

        $this->serie->genres()->sync($genre_ids);

        $this->serie->overview = $serie->getOverview();;
        $this->serie->imdbid = $serie->getImdbId();
        $this->serie->rating = $serie->getSiteRating();
        $this->serie->poster = $seriePoster;
        $this->serie->fanart = $serieFanart;
        $this->serie->status = $serie->getStatus();

        $episodes = [];
        $page = 1;
        do {
            try { $serieEpisodes = $serieExtension->getEpisodes($this->serie->tvdbid, $page);
            } catch (\Exception $e) {
                break;
            }

            foreach ($serieEpisodes->getData() as $episode) {
                $episodes[] = $e = Episode::firstOrNew(['episodeid' => $episode->getId()]);
                $e->name = $episode->getEpisodeName();
                $e->overview = $episode->getOverview();
                $e->aired = $episode->getFirstAired();
                $e->episodeNumber = $episode->getAiredEpisodeNumber();
                $e->episodeSeason = $episode->getAiredSeason();
                $e->episodeid = $episode->getId();
            }
        } while ($page = $serieEpisodes->getLinks()->getNext());

        $this->serie->episodes()->saveMany($episodes);
        $this->serie->touch();
        $this->serie->save();
    }
}
