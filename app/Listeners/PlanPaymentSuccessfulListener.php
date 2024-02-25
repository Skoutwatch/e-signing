<?php

namespace App\Listeners;

use App\Events\Subscription\PlanPaymentConfirmation;
use App\Mail\PlanPaymentConfirmationEmail;
use Illuminate\Support\Facades\Mail;

class PlanPaymentSuccessfulListener
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
     * @return void
     */
    public function handle(PlanPaymentConfirmation $event)
    {
        Mail::send(new PlanPaymentConfirmationEmail($event->transaction, $event->plan));
    }
}
