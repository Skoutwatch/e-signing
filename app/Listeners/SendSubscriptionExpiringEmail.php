<?php

namespace App\Listeners;

use App\Events\Subscription\SubscriptionExpiring;
use App\Mail\YourSubscriptionExpiringMail;
use Illuminate\Support\Facades\Mail;

class SendSubscriptionExpiringEmail
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
     * @param  \App\Events\SubscriptionExpiring  $event
     * @return void
     */
    public function handle(SubscriptionExpiring $event)
    {
        return Mail::to('ask@gettonote.com')->send(new YourSubscriptionExpiringMail($event->details));
    }
}
