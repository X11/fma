<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Episode;
use App\Serie;

class FetchSerieEpisodes extends Job implements ShouldQueue
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
        $info = \TVDB::getTvShowAndEpisodes($this->serie->tvdbid);
        $episodes = [];
        foreach ($info['episodes'] as $episode) {
            $episodes[] = $e = Episode::firstOrNew(['episodeid' => $episode->getId()]);
            $e->name = $episode->getName();
            $e->overview = $episode->getOverview();
            $e->aired = $episode->getFirstAired();
            $e->episodeNumber = $episode->getEpisodeNumber();
            $e->episodeSeason = $episode->getSeasonNumber();
            $e->episodeid = $episode->getId();
        }
        $this->serie->episodes()->saveMany($episodes);
        $this->serie->save();
    }
}
