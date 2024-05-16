<?php

namespace App\Events;

use App\Models\Workpack;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WorkpackCompleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Workpack
     */
    public $workpack;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct( Workpack $workpack )
    {
        $this->workpack = $workpack;
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
