<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Auth;
use Seedr\Seedr;

class TransferEpisodeFile extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    private $userId;
    private $fileId;
    private $seedrFileId;
    private $seedrFileName;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userId, $fileId, $seedrFileId, $seedrFileName)
    {
        $this->userId = $userId;
        $this->fileId = $fileId;
        $this->seedrFileId = $seedrFileId;
        $this->seedrFileName = $seedrFileName;
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
        $file = $user->files()->findOrFail($this->fileId);

        if ($file->status != "Downloaded")
            return;

        $u = $user->settings->seedr_user;
        $p = $user->settings->seedr_pass;

        $s = new Seedr($u, $p);

        $file->status = "Transfering";
        $file->save();

        $ext = pathinfo($this->seedrFileName, PATHINFO_EXTENSION);
        if ($ext != 'mp4'){
            $fName = $this->fileId . $this->seedrFileName . '.mp4';
            \Log::info(env('SEEDR_DOWNLOAD_PATH') . '/' . $fName);
            $s->downloadFile(env('SEEDR_DOWNLOAD_PATH') . '/' . $fName, $this->seedrFileId . '/mp4');
        } else {
            $fName = $this->fileId . $this->seedrFileName;
            \Log::info(env('SEEDR_DOWNLOAD_PATH') . '/' . $fName);
            $s->downloadFile(env('SEEDR_DOWNLOAD_PATH') . '/' . $fName, $this->seedrFileId);
        }

        $file->status = "Done";
        $file->file = $fName;
        $file->save();

    }
}
