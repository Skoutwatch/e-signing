<?php

namespace App\Events\Team;

use App\Models\TeamUser;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TeamMemberInvitation
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $detail;

    public function __construct(TeamUser $detail)
    {
        $this->detail = $detail;
    }
}
