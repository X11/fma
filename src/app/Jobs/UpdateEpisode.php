<?php

namespace App\Jobs;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Jobs\Job;
use App\Episode;
use App;
use App\Person;

class UpdateEpisode extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    private $episode;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Episode $episode)
    {
        $this->episode = $episode;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client = App::make('tvdb');

        // Get serie from TVDB
        $episodeExtension = $client->episodes();
        $episode = $episodeExtension->get($this->episode->episodeid);

        // Set general information
        $this->episode->imdbid = $episode->getImdbId();
        $this->episode->rating = $episode->getSiteRating();
        $this->episode->image = $episode->getFilename();

        $guestIds = [];
        foreach($episode->getGuestStars() as $guest){
            if ($guest != ""){
                $person = Person::firstOrNew([
                    'name' => $guest
                ]);
                $person->name = $guest;
                $person->save();

                $guestIds[] = $person->id;
            }
        }
        $this->episode->guests()->sync($guestIds);

        $writerIds = [];
        foreach($episode->getWriters() as $writer){
            if ($writer != ""){
                $person = Person::firstOrNew([
                    'name' => $writer
                ]);
                $person->name = $writer;
                $person->save();

                $writerIds[] = $person->id;
            }
        }
        $this->episode->writers()->sync($writerIds);

        $directors = explode('|', $episode->getDirector());
        $directorIds = [];
        foreach($directors as $director){
            if ($director != ""){
                $person = Person::firstOrNew([
                    'name' => $director
                ]);
                $person->name = $director;
                $person->save();

                $directorIds[] = $person->id;
            }
        }
        $this->episode->directors()->sync($directorIds);

        $this->episode->save();
    }
}
