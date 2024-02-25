<?php

namespace App\Mail\Signlink;

use App\Models\Document;
use App\Models\SignlinkDocumentUserFormData;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SignlinkDocumentNotifySignerMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public SignlinkDocumentUserFormData $userFormData,
        public Document $document,
        public Document $parentDocument
    ) {
    }

    public function envelope()
    {
        return new Envelope(
            subject: 'Your Signlink document has been signed',
        );
    }

    public function content()
    {
        return new Content(
            markdown: 'emails.signlink.SignlinkDocumentNotifySigner',
            with: ['link' => config('externallinks.s3_storage_url').$this->userFormData?->file_url],
        );
    }

    public function attachments()
    {
        return Attachment::fromPath(config('externallinks.s3_storage_url').$this->userFormData?->file_url);
    }
}
