<?php

namespace App\Jobs;

use DB;
use Log;
use App\Models\Action;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ActionDisabledJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 4000;

    private $actionId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($actionId)
    {
        $this->actionId = $actionId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $action = Action::find($this->actionId) ?? null;

        if($action) {
            $action->delete();
        }       
    }
}
