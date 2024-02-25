<?php

namespace App\Listeners;

use App\Events\Subscription\TrialPlanEvent;
use App\Mail\TrialUserEmail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class SendTrialUserEmail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(public User $user)
    {
        //
    }

    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle(TrialPlanEvent $event)
    {
        Mail::send(new TrialUserEmail($event->detail));
    }
}
