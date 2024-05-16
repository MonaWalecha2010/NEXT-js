<?php

namespace App\Events;

use App\Models\Workpack;
use App\Models\WorkpackPanel;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WorkpackPanelChanged
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
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct( WorkpackPanel $workpackPanel)
    {
        $this->workpackPanel = $workpackPanel;
        $this->workpack = $workpackPanel->workpack;

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
