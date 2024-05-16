<?php

namespace App\Listeners;

use App\Events\WorkpackChanged;
use App\Events\WorkpackPanelTaskCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateWorkpackStats
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
    public function handle(WorkpackChanged $event)
    {
        $workpack = $event->workpack;
        $stats = $workpack->generateWorkpackStatsBasic();
        $workpack->setNote(  $stats, 'stats' );
        $schematicStats = $workpack->generateWorkpackSchematicStats();
        $workpack->setNote( $schematicStats, 'schematic_stats' );
        $workpack->save();
    }
}
