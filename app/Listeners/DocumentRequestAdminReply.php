<?php

namespace App\Listeners;

use App\Events\document\DocumentRequestAdminAction;
use App\Mail\AffidavitRequestCancelledEmail;
use App\Mail\AffidavitRequestCompletedEmail;
use App\Mail\AffidavitRequestInviewEmail;
use Illuminate\Support\Facades\Mail;

class DocumentRequestAdminReply
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
    public function handle(DocumentRequestAdminAction $event)
    {
        if ($event->detail->status == 'Completed') {
            return Mail::send(new AffidavitRequestCompletedEmail($event->detail, $event->session));
        } elseif ($event->detail->status == 'Cancelled') {
            return Mail::send(new AffidavitRequestCancelledEmail($event->detail, $event->session));
        } elseif ($event->detail->status == 'In-view') {
            return Mail::send(new AffidavitRequestInviewEmail($event->detail, $event->session));
        }
    }
}
