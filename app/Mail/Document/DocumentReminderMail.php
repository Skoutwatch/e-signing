<?php

namespace App\Mail\Document;

use App\Models\Document;
use App\Models\DocumentParticipant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DocumentReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $code;

    public $verify_status;

    public $email;

    public $state;

    public $root_domain;

    public $link;

    public $role;

    public $policy_link;

    public $app_url;

    public $action;

    public $document_owner;

    public $participant_signing;

    public function __construct(public Document $document, public DocumentParticipant $participant)
    {
        $this->document_owner = $this->participant->user_id == $this->document->user_id ? true : false;

        $this->action = match (true) {
            $this->participant->role == 'Signer' => 'sign',
            $this->participant->role == 'Approver' => 'approve',
            default => 'unknown',
        };

        $this->code = $this->participant?->user?->isset_password == 1 ? $this->participant?->otp : null;

        $this->verify_status = ! empty($code) ? '0' : '1';

        $this->email = $this->action == 'owner' ? $this->document?->user->email : $this->participant?->email;

        $this->link = config('externallinks.verify_document_url')."?e={$this->email}&f={$this->verify_status}&document_id=".$this->document->id.'&entry_point='.$this->document->entry_point.'&access_code='.$this->code;

        $this->app_url = config('externallinks.verify_document_url') ? '' : 'Testing : ';
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
            markdown: 'emails.document.DocumentReminder',
        );
    }

    public function subjectAction()
    {
        return match (true) {
            ($this->participant->role == 'Signer' ||
            $this->participant->role == 'Approver') &&
            $this->document_owner == true => "Action Required to your document: Please {$this->action} {$this->document->title}",

            ($this->participant->role == 'Signer' ||
            $this->participant->role == 'Approver') &&
            $this->document_owner == false => "Action Required: Please {$this->action} {$this->document->title}",
            default => 'Unknown Action Required',
        };
    }
}
