<?php

namespace App\Events;

use App\Models\Workpack;
use App\Models\WorkpackPanel;
use App\Models\WorkpackPanelTask;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WorkpackPanelTaskCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Workpack
     */
    public $workpack;

    /**
     * @var WorkpackPanel
     */
    public $workpackPanel;

    /**
     * @var WorkpackPanelTask
     */
    public $workpackPanelTask;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct( WorkpackPanelTask $workpackPanelTask )
    {
        $this->workpackPanelTask = $workpackPanelTask;
        $this->workpackPanel = $workpackPanelTask->workpack_panel;
        $this->workpack = $workpackPanelTask->workpack;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
