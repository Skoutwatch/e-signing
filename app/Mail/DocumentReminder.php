<?php

namespace App\Mail;

use App\Models\Document;
use App\Models\DocumentParticipant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DocumentReminder extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Document $document, public DocumentParticipant $participant)
    {
    }

    public function build()
    {
        $link = config('externallinks.verify_document_url').$this->document?->file_url;

        return $this->subject("Action Required: {$this->document->title}")
            ->view('emails.document.DocumentReminder')->with('link', $link);
    }
}
