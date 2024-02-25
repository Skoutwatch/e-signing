<?php

namespace App\Listeners\Schedule;

use App\Mail\Schedule\ScheduleSessionParticipantEmail;
use Illuminate\Support\Facades\Mail;

class ScheduleParticipantWhileOnCallListener
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
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        Mail::send(new ScheduleSessionParticipantEmail($event->document, $event->documentParticipant, $event->schedule));
    }
}
