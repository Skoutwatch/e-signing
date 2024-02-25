<?php

namespace App\Mail\Document;

use App\Models\Document;
use App\Models\DocumentUpload;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DocumentShareMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Document $document, public DocumentUpload $completedDocument, public $email)
    {
    }

    public function envelope()
    {
        return new Envelope(
            subject: 'Here’s your document',
        );
    }

    public function content()
    {
        return new Content(
            markdown: 'emails.document.DocumentShareEmail',
            with: ['link' => config('externallinks.s3_storage_url').$this->completedDocument?->file_url],
        );
    }

    public function attachments()
    {
        return Attachment::fromPath(config('externallinks.s3_storage_url').$this->completedDocument?->file_url);
    }
}
