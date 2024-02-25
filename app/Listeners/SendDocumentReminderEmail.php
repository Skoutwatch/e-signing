<?php

namespace App\Listeners;

use App\Events\DocumentReminderEvent;
use App\Mail\DocumentReminderMail;
use Illuminate\Support\Facades\Mail;

class SendDocumentReminderEmail
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

    public function handle(DocumentReminderEvent $event)
    {
        return Mail::to('ask@gettonote.com')->send(new DocumentReminderMail($event->details));
    }
}
