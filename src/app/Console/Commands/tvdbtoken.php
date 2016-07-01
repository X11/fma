<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App;
use Cache;

class tvdbtoken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tvdb:token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get TVDB token';

    /**
     * Create a new command instance.
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
        $client = App::make('tvdb');

        $this->info(Cache::get('tvdb_token'));
    }
}
