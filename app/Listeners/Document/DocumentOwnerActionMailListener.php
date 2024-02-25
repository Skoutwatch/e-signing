<?php

namespace App\Listeners\Document;

use App\Mail\Document\DocumentOwnerActionMail;
use Illuminate\Support\Facades\Mail;

class DocumentOwnerActionMailListener
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
        Mail::send(new DocumentOwnerActionMail($event->document));
    }
}
