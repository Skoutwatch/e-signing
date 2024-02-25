<?php

namespace App\Listeners\Document;

use App\Mail\Document\DocumentCompletedMail;
use Illuminate\Support\Facades\Mail;

class DocumentCompletedListener
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
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        return Mail::send(new DocumentCompletedMail($event->document, $event->completedDocument, $event->participant));
    }
}
