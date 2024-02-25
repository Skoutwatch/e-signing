<?php

namespace App\Listeners;

use App\Events\Document\DocumentRequestCompleted;
use App\Mail\AffidavitRequestCompletedEmail;
use Illuminate\Support\Facades\Mail;

class DocumentRequestComplete
{
    public function __construct()
    {
        //
    }

    public function handle(DocumentRequestCompleted $event)
    {
        return Mail::send(new AffidavitRequestCompletedEmail($event->detail));
    }
}
