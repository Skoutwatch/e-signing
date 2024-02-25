<?php

namespace App\Mail\Document;

use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DocumentReminderForApproverMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Document $document)
    {
        $this->document = $document;
    }

    public function build()
    {
        return $this->subject("Action Required: Please Approve {$this->document->title}")
            ->view('emails.document.DocumentReminderForApprover');
    }
}
