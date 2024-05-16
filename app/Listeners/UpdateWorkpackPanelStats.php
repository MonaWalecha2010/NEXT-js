<?php

namespace App\Listeners;

use App\Events\WorkpackPanelTaskCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateWorkpackPanelStats
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\WorkpackPanelTaskCreated  $event
     * @return void
     */
    public function handle(WorkpackPanelTaskCreated $event)
    {
        $workpackPanelTask = $event->workpackPanelTask;
        $workpackPanel = $event->workpack;
    }
}
