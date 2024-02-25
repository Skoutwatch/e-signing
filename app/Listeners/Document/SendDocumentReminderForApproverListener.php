<?php

namespace App\Listeners\Document;

use App\Events\Document\DocumentReminderForApproverEvent;
use App\Mail\Document\DocumentReminderMail;
use Illuminate\Support\Facades\Mail;

class SendDocumentReminderForApproverListener
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
    public function handle(DocumentReminderForApproverEvent $event): void
    {
        $document = $event->document;
        $participant = $event->participant;
        Mail::to($participant->email)->send(new DocumentReminderMail($document));
    }
}
