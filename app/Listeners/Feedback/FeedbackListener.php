<?php

namespace App\Listeners\Feedback;

use App\Mail\Feedback\CustomerFeedbackMail;
use Illuminate\Support\Facades\Mail;

class FeedbackListener
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
        // return Mail::to('ask@gettonote.com')->send(new CustomerFeedbackMail($event->details));
        return Mail::to('gene.domynic@feerock.com')->send(new CustomerFeedbackMail($event->details));
    }
}
