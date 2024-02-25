<?php

namespace App\Listeners\Schedule;

use App\Mail\Schedule\Notary\AcceptRejectRequestMail;
use Illuminate\Support\Facades\Mail;

class ScheduleNotaryAcceptOrRejectCustomerListener
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
        Mail::send(new AcceptRejectRequestMail($event->schedule));
    }
}
