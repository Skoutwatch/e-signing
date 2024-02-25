<?php

namespace App\Listeners;

use App\Events\Team\TeamMemberInvitation;
use App\Traits\Api\EmailTraits;
use Illuminate\Support\Facades\Mail;

class TeamMemberInvitationListener
{
    use EmailTraits;

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
     * @return void
     */
    public function handle(TeamMemberInvitation $event)
    {
        return Mail::send(new \App\Mail\TeamMemberInvitation($event->detail));
    }
}
