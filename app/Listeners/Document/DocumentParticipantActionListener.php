<?php

namespace App\Listeners\Document;

use App\Mail\Document\DocumentParticipantActionEmail;
use Illuminate\Support\Facades\Mail;

class DocumentParticipantActionListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        Mail::send(new DocumentParticipantActionEmail($event->document, $event->participant, $event->currentParticipant));
    }
}
