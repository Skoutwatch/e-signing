<?php

namespace App\Listeners\Document;

use App\Events\Document\DocumentApproverEvent;
use App\Mail\Document\DocumentApproverMail;
use Illuminate\Support\Facades\Mail;

class DocumentApproverListener
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
    public function handle(DocumentApproverEvent $event): void
    {
        $document = $event->document;
        $approvers = $document->approvers;

        foreach ($approvers as $approver) {
            Mail::to($approver->email)->send(new DocumentApproverMail($document));
        }
    }
}
