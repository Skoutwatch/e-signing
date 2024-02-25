<?php

namespace App\Events\Schedule\Notary;

use App\Models\ScheduleSession;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AcceptOrRejectCustomerEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(public ScheduleSession $schedule)
    {
    }
}
