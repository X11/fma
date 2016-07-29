<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Serie;
use Illuminate\Support\Facades\App;
use App\Jobs\UpdateSerieAndEpisodes;

class UpdateSeries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:series';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $needUpdate = [];
        $series = Serie::all();
        $client = App::make('tvdb');
        $serieExtension = $client->series();

        foreach($series as $serie){
            if ($serie->updated_at < $serieExtension->getLastModified($serie->tvdbid)){
                dispatch(new UpdateSerieAndEpisodes($serie));
            }
        }
    }
}
