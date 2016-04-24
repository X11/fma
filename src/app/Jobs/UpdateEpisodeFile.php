<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;

use Auth;
use Seedr\Seedr;
use App\Jobs\TransferEpisodeFile;

class UpdateEpisodeFile extends Job
{
    use InteractsWithQueue, SerializesModels, DispatchesJobs;

    private $userId;
    private $fileId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userId, $fileId)
    {
        $this->userId = $userId;
        $this->fileId = $fileId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = Auth::loginUsingId($this->userId);
        $file = $user->files()->findOrFail($this->fileId);

        if ($file->status != "Downloading")
            return;

        $u = $user->settings->seedr_user;
        $p = $user->settings->seedr_pass;

        $s = new Seedr($u, $p);

        $content= $s->getFolder();
        $files = null;
        foreach ($content->folders as $folder) {
            if ($folder->name == $file->name){
                $files = $s->getFolder($folder->id)->files;
                break;
            }
        }

        if ($files){
            foreach ($files as $f) {
                if ($f->stream_video){
                    $file->status = "Downloaded";
                    $file->save();
                    dispatch(new TransferEpisodeFile($user->id, $file->id, $f->id, $f->name));
                    break;
                }
            }
        }
    }
}
