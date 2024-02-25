<?php

namespace App\Listeners;

use App\Events\Subscription\SubscriptionExpiredEvent;
use App\Mail\YourSubscriptionExpiredMail;
use Illuminate\Support\Facades\Mail;

class SendSubscriptionExpiredEmail
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
    public function handle(SubscriptionExpiredEvent $event)
    {
        return Mail::to('ask@gettonote.com')->send(new YourSubscriptionExpiredMail($event->details));
    }
}
