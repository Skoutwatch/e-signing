<?php

namespace App\Mail\Document;

use App\Models\Document;
use App\Models\DocumentParticipant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class DocumentParticipantActionEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $comment;

    public $code;

    public $link;

    public $email;

    public $role;

    public $action;

    public $document_owner;

    public $participant_that_signed;

    public $kind_of_participant;

    public $verify_status;

    /**
     * Create a new message instance.
     */
    public function __construct(public Document $document, public DocumentParticipant $participant, public DocumentParticipant $currentParticipant)
    {
        $this->email = $this->participant?->email;

        $this->comment = $this->currentParticipant->comment;

        $this->code = $this->participant?->user?->isset_password == 1 ? $this->participant?->otp : null;

        $this->verify_status = ! empty($code) ? '0' : '1';

        $this->link = config('externallinks.verify_document_url')."?e={$this->email}&f={$this->verify_status}&document_id=".$this->document->id.'&entry_point='.$this->document->entry_point.'&access_code='.$this->code;

        $this->document_owner = $this->participant->user_id == $this->participant->who_added_id ? true : false;

        $this->participant_that_signed = $this->participant->user_id == $this->currentParticipant->user_id ? true : false;

        $this->kind_of_participant = $this->action = match (true) {
            ($this->currentParticipant->user_id == $this->document->user_id) ||
            ($this->currentParticipant->user_id == $this->participant->user_id) => 'current_user_action',

            ($this->participant->role == 'Signer' ||
            $this->participant->role == 'Approver') &&
            $this->document_owner == false => 'participant',

            default => 'participant'
        };

        $this->action = $this->currentParticipant->status;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            to: [$this->email],
            subject: $this->subjectAction(),

        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.document.DocumentParticipantActionEmail',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }

    public function subjectAction()
    {
        Log::debug($this->kind_of_participant);
        Log::debug($this->participant);
        Log::debug($this->document);
        Log::debug($this->currentParticipant);

        return match (true) {
            $this->document_owner === true => 'Your document has been '.strtolower($this->currentParticipant->status),

            $this->currentParticipant->user_id === $this->participant->user_id => 'You just '.strtolower($this->currentParticipant->status).' a document that was shared with you',

            ($this->participant->role == 'Signer' ||
            $this->participant->role == 'Approver') &&
            $this->document_owner == false => 'A document shared with you has been '.strtolower($this->currentParticipant->status),
        };
    }
}
