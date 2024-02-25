<?php

namespace App\Listeners\Schedule\Customer;

use App\Mail\Schedule\Customer\RejectedRequestMail;
use Illuminate\Support\Facades\Mail;

class RejectedRequestListener
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
        Mail::send(new RejectedRequestMail($event->schedule));
    }
}
