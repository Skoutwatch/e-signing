<?php

namespace App\Listeners\Document;

use App\Mail\Document\DocumentParticipantSendEmail;
use Illuminate\Support\Facades\Mail;

class DocumentParticipantSendMailListener
{
    public function __construct()
    {
    }

    public function handle(object $event): void
    {
        Mail::send(new DocumentParticipantSendEmail($event->document, $event->participant));
    }
}
