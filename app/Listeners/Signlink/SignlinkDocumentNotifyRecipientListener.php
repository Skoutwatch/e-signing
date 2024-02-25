<?php

namespace App\Listeners\Signlink;

use App\Mail\Signlink\SignlinkDocumentNotifyRecipientMail;
use Illuminate\Support\Facades\Mail;

class SignlinkDocumentNotifyRecipientListener
{
    public function __construct()
    {
        //
    }

    public function handle($event)
    {
        return Mail::to($event->parentDocument?->user?->email)->send(new SignlinkDocumentNotifyRecipientMail($event->userFormData, $event->document, $event->parentDocument));
    }
}
