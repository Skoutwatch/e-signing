<?php

namespace App\Listeners;

use App\Events\Subscription\PaymentConfirmation;
use App\Mail\AdminAffidavitRequestNotification;
use App\Mail\PaymentConfirmationEmail;
use App\Mail\RequestSuccessfulNotification;
use Illuminate\Support\Facades\Mail;

class PaymentSuccessfulListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle(PaymentConfirmation $event)
    {
        // payment confirmation to user
        Mail::send(new PaymentConfirmationEmail($event->transaction));

        // Admin notification
        Mail::send(new AdminAffidavitRequestNotification($event->transaction));

        // User Request successful
        Mail::send(new RequestSuccessfulNotification($event->transaction));
    }
}
