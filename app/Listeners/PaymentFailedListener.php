<?php

namespace App\Listeners;

use App\Events\Subscription\PaymentFailedEvent;
use App\Mail\PaymentFailedEmail;
use Mail;

class PaymentFailedListener
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
    public function handle(PaymentFailedEvent $event): void
    {
        // payment confirmation to user
        Mail::send(new PaymentFailedEmail($event->transaction));
    }
}
