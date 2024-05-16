<?php

namespace App\Listeners;

use App\Events\WorkpackPanelChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateWorkpackSchematicStats
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
     * @param  \App\Events\WorkpackPanelChanged  $event
     * @return void
     */
    public function handle( WorkpackPanelChanged $event)
    {
        $workpackPanel = $event->workpackPanel;
    }
}
