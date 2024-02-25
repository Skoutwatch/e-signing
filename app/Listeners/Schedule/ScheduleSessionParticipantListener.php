<?php

namespace App\Listeners\Schedule;

use App\Mail\Schedule\ScheduleSessionParticipantEmail;
use Illuminate\Support\Facades\Mail;

class ScheduleSessionParticipantListener
{
    public function __construct()
    {
        //
    }

    public function handle($event)
    {
        foreach ($event->document?->participants as $participant) {
            ($participant->user_id != $event->document->user_id) ? Mail::send(new ScheduleSessionParticipantEmail($event->document, $participant, $event->schedule)) : null;
        }
    }
}
