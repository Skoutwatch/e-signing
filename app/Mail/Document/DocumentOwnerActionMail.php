<?php

namespace App\Mail\Document;

use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DocumentOwnerActionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $completedDoc;

    public $documentParticpants;

    public $sendToMyself;

    /**
     * Create a new message instance.
     */
    public function __construct(public Document $document)
    {
        $this->completedDoc = $this->document?->completedDocument?->file_url;

        $this->documentParticpants = $this->document->participants->where('user_id', '!=', $this->document?->user->id)->where('notification_count', '=', 1)->pluck('first_name')->toArray();

        $this->sendToMyself = $this->document->participants->where('user_id', $document?->user->id)->where('notification_count', '=', 1)->first() && empty($this->documentParticpants) ? true : false;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            to: [$this->document?->user?->email],
            subject: "Your document has been {$this->document->status}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.document.DocumentOwnerActionMail',
            with: ['link' => $this->completedDoc ? config('externallinks.s3_storage_url').$this->completedDoc : null],
        );
    }

    public function attachments()
    {
        return $this->document->completedDocument?->file_url ? Attachment::fromPath(config('externallinks.s3_storage_url').$this->document->completedDocument?->file_url) : null;
    }
}
