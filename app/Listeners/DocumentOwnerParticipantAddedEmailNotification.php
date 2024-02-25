<?php

namespace App\Listeners;

use App\Events\Document\ParticipantAdded;
use App\Mail\DocumentOwnerParticipantAddedEmail;
use Illuminate\Support\Facades\Mail;

class DocumentOwnerParticipantAddedEmailNotification
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
     * @return void
     */
    public function handle(ParticipantAdded $event)
    {
        return Mail::send(new DocumentOwnerParticipantAddedEmail($event->details));
    }
}
