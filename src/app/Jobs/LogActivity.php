<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Activity;

class LogActivity extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $user_id;
    protected $IP;
    protected $type;
    protected $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user_id, $type, $data, $IP)
    {
        $this->user_id = $user_id; 
        $this->IP = $IP;
        $this->type = $type;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Activity::create([
            'user_id' => $this->user_id,
            'IP' => $this->IP,
            'type' => $this->type,
            'data' => $this->data,
        ]);
    }
}
