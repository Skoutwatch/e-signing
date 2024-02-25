<?php

namespace App\Listeners\Schedule\Customer;

use App\Mail\Schedule\Customer\AcceptedRequestMail;
use Illuminate\Support\Facades\Mail;

class AcceptedRequestListener
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
        Mail::send(new AcceptedRequestMail($event->schedule));
    }
}
