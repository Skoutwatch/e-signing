<?php

namespace App\Listeners\Document;

use App\Mail\Document\DocumentOwnerParticipantActionMail;
use Illuminate\Support\Facades\Mail;

class DocumentOwnerParticipantActionListener
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
        Mail::send(new DocumentOwnerParticipantActionMail($event->participant));
    }
}
