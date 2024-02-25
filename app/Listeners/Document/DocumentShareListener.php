<?php

namespace App\Listeners\Document;

use App\Mail\Document\DocumentShareMail;
use Illuminate\Support\Facades\Mail;

class DocumentShareListener
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
        return Mail::to($event->email)->send(new DocumentShareMail($event->document, $event->completedDocument, $event->email));
    }
}
