<?php

namespace App\Listeners\Signlink;

use App\Mail\Signlink\SignlinkDocumentNotifySignerMail;
use Illuminate\Support\Facades\Mail;

class SignlinkDocumentNotifySignerListener
{
    public function __construct()
    {
        //
    }

    public function handle($event)
    {
        return Mail::to($event->userFormData->email)->send(new SignlinkDocumentNotifySignerMail($event->userFormData, $event->document, $event->parentDocument));
    }
}
