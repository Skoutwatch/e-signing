<?php

namespace App\Listeners\Signlink;

use App\Mail\Signlink\SignlinkShareLinkMail;
use Illuminate\Support\Facades\Mail;

class SignlinkShareLinkListener
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
        return Mail::to($event->email)->send(new SignlinkShareLinkMail($event->document, $event->email));
    }
}
