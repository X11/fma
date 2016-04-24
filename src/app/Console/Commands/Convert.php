<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\EpisodeFile;

class Convert extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'video:convert {episodeFileId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert video to webm';

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
        $file = EpisodeFile::findOrFail($this->argument('episodeFileId'));

        $ext = pathinfo($file->file, PATHINFO_EXTENSION);
        if ($ext == 'webm' || $ext == 'mpv')
            return 0;

        $dataPath = env('SEEDR_DOWNLOAD_PATH') . '/';

        system('ffmpeg -i "' . $dataPath . $file->file . '" "' . $dataPath . $file->file . '.webm" -acodec libvorbis -ac 2 -ab 96k -ar 44100 -b 345k');

        $file->status = "Done";
        $file->file = $file->file . '.webm';
        $file->save();
    }
}
