<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Auth;
use App\EpisodeFile;
use Seedr\Seedr;

class DownloadEpisodeFile extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    private $userId;
    private $file;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userId, EpisodeFile $file)
    {
        $this->userId = $userId;
        $this->file= $file;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = Auth::loginUsingId($this->userId);

        $u = $user->settings->seedr_user;
        $p = $user->settings->seedr_pass;

        $s = new Seedr($u, $p);
        $torrent = $s->addTorrentFromMagnet($this->file->magnet);

        if(isset($torrent->title)){
            $this->file->name = $torrent->title;
            $this->file->status = "Downloading";
            $this->file->save();
        } else {
            \Log::info($this->userId . ' ' .$torrent->error);
            $this->file->delete();
        }

    }
}
