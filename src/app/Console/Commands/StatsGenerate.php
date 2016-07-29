<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Serie;
use App\Episode;
use App\Person;
use App\User;
use Carbon\Carbon;

class StatsGenerate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stats:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates stats for the day';

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
        $serieCount = Serie::count();
        $episodeCount = Episode::count();
        $userCount = User::count();
        $peopleCount = Person::count();

        $today = Carbon::now();
        $yesterday = Carbon::now()->subDay();

        $loginCount = DB::table('activities')
                        ->selectRaw('COUNT(*) as aggregate')
                        ->where('type', 'account')
                        ->where('action', 'login')
                        ->whereBetween('created_at', [$yesterday, $today])
                        ->first()
                        ->aggregate;

        $watchedCount = DB::table('activities')
                        ->selectRaw('COUNT(*) as aggregate')
                        ->where('type', 'episode')
                        ->where('action', 'watched')
                        ->whereBetween('created_at', [$yesterday, $today])
                        ->first()
                        ->aggregate;

        DB::table('stats')->insert([
            // Counters
            ['key' => 'serie.count', 'value' => $serieCount, 'created_at' => $today, 'updated_at' => $today],
            ['key' => 'episode.count', 'value' => $episodeCount, 'created_at' => $today, 'updated_at' => $today],
            ['key' => 'user.count', 'value' => $userCount, 'created_at' => $today, 'updated_at' => $today],
            ['key' => 'person.count', 'value' => $peopleCount, 'created_at' => $today, 'updated_at' => $today],

            // Daily usage
            ['key' => 'logins', 'value' => $loginCount, 'created_at' => $today, 'updated_at' => $today],
            ['key' => 'episode.watched', 'value' => $watchedCount, 'created_at' => $today, 'updated_at' => $today],
        ]);
    }
}
