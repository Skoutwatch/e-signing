<?php

namespace App\Mail\Document;

use App\Models\Document;
use App\Models\DocumentParticipant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DocumentCompletedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $link;

    public $status;

    public $email;

    public $role;

    public $action;

    public $document_owner;

    public $participant_that_signed;

    public $kind_of_participant;

    public $verify_status;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(public Document $document, public $completedDocument, public DocumentParticipant $participant)
    {
        $this->document_owner = $this->participant->user_id == $this->document->user_id ? true : false;

        $this->status = strtolower($this->document->status);

        $this->kind_of_participant = match (true) {
            $this->document_owner === true => 'owner',

            $this->participant->role == 'Signer' ||
            $this->participant->role == 'Approver' &&
            $this->document_owner == false => 'participant',

            default => 'participant',
        };
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        $recipient = $this->document_owner ? ['system@example.com'] : [$this->participant->email];

        return new Envelope(
            subject: $this->subjectAction(),
            to : $recipient
        );
    }

    public function content()
    {
        return new Content(
            markdown: 'emails.document.DocumentCompletedEmail',
            with: ['link' => $this->completedDocument ? config('externallinks.s3_storage_url').$this->completedDocument?->file_url : null],
        );
    }

    public function attachments()
    {
        return $this->completedDocument?->file_url ? Attachment::fromPath(config('externallinks.s3_storage_url').$this->completedDocument?->file_url) : null;
    }

    public function subjectAction()
    {
        return match (true) {
            $this->document_owner === true => "Your document has been {$this->status}",

            $this->participant->role == 'Signer' ||
            $this->participant->role == 'Approver' &&
            $this->document_owner == false => "The document shared with you has been {$this->status}",

            default => "Your document has been {$this->status}"
        };
    }
}
