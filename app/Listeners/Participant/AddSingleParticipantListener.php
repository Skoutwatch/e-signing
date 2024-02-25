<?php

namespace App\Listeners\Participant;

use App\Events\Participant\AddSingleParticipantEvent;
use App\Mail\Schedule\AddedAsParticipantEmail;
use App\Mail\Schedule\AddedAsViewerEmail;
use App\Traits\Api\EmailTraits;
use App\Traits\Api\OtpTraits;
use Illuminate\Support\Facades\Mail;

class AddSingleParticipantListener
{
    use EmailTraits, OtpTraits;

    public function __construct()
    {

    }

    public function handle(AddSingleParticipantEvent $event)
    {
        $event->participant = $event->participant;

        if ($event->participant->user_id != $event->participant->document->user_id) {
            if (strtolower($event->participant->role) == 'signer') {
                Mail::send(new AddedAsParticipantEmail($event->participant->document, $event->participant->role, $event->participant?->document?->schedule));
            } elseif (strtolower($event->participant->role) == 'viewer') {
                Mail::send(new AddedAsViewerEmail($event->participant->document, $event->participant, $event->participant?->document?->schedule));
            }
        }
    }
}
