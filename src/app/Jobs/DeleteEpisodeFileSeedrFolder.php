<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Seedr\Seedr;

class DeleteEpisodeFileSeedrFolder extends Job
{
    use InteractsWithQueue, SerializesModels;

    private $userId;
    private $seedrFolderName;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userId, $seedrFolderName)
    {
        $this->userId = $userId;
        $this->seedrFolderName = $seedrFolderName;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $user = Auth::loginUsingId($this->userId);

        $u = $user->settings->seedr_user;
        $p = $user->settings->seedr_pass;

        $s = new Seedr($u, $p);

        $content= $s->getFolder();

        if (isset($content->folders)){
            foreach ($content->folders as $folder) {
                if ($folder->name == $this->seedrFolderName){
                    $s->deleteFolder($folder->id);
                    break;
                }
            }
        }
    }
}
