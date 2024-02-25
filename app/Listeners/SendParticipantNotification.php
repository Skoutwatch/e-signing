<?php

namespace App\Listeners;

use App\Events\Document\ParticipantAdded;
use App\Mail\AddedAsParticipantEmail;
use App\Mail\AddedAsViewerEmail;
use App\Mail\DocumentOwnerParticipantAddedEmail;
use Illuminate\Support\Facades\Mail;

class SendParticipantNotification
{
    public function __construct()
    {
    }

    public function handle(ParticipantAdded $event)
    {
        $participants = $event->document?->participants;

        foreach ($participants as $participant) {
            if ($participant->user_id != $event->document->user_id) {
                if (strtolower($participant['role']) == 'signer') {
                    Mail::send(new AddedAsParticipantEmail($event->document, $participant, $event->message));
                } elseif (strtolower($participant['role']) == 'viewer') {
                    Mail::send(new AddedAsViewerEmail($event->document, $participant, $event->message));
                }
            }
        }

        Mail::send(new DocumentOwnerParticipantAddedEmail($event->document));
    }
}
