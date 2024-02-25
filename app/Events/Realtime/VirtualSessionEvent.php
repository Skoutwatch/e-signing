<?php

namespace App\Events\Realtime;

use App\Http\Resources\Schedule\ScheduleSessionResource;
use App\Models\ScheduleSession;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VirtualSessionEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $state;

    public function __construct(public ScheduleSession $session)
    {
        $this->state = (new ScheduleSessionResource($session))->jsonSerialize();
    }

    public function broadcastAs()
    {
        return 'VirtualSessionUpdate';
    }

    public function broadcastOn()
    {
        return new Channel('virtual-session-update'.$this->session->id);
    }
}
