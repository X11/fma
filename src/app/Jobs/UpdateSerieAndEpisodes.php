<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Episode;
use App\Serie;
use App;

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

        $this->serie->overview = $serie->getOverview();;
        $this->serie->imdbid = $serie->getImdbId();
        $this->serie->rating = $serie->getSiteRating();
        $this->serie->poster = $seriePoster;

        $episodes = [];
        $page = 1;
        do {
            $serieEpisodes = $serieExtension->getEpisodes($this->serie->tvdbid, $page);

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
