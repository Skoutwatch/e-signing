<?php

namespace App\Listeners;

use App\Events\Schedule\ScheduleResendAuthOtp;
use App\Mail\Schedule\ScheduleResendAuthOtpEmail;
use Illuminate\Support\Facades\Mail;

class ScheduleResendAuthOtpListener
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
    public function handle(ScheduleResendAuthOtp $event)
    {
        return Mail::send(new ScheduleResendAuthOtpEmail($event->participant));
    }
}
