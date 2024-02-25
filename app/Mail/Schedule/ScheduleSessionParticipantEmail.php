<?php

namespace App\Mail\Schedule;

use App\Models\Document;
use App\Models\DocumentParticipant;
use App\Models\ScheduleSession;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ScheduleSessionParticipantEmail extends Mailable
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

    public function __construct(public Document $document, public DocumentParticipant $participant, public ScheduleSession $schedule)
    {
        $this->code = $this->participant?->otp;

        $this->verify_status = ! empty($this->code) ? '0' : '1';

        $this->email = $this->participant?->email;

        $this->policy_link = config('externallinks.tonote_policy_public_url');

        $this->root_domain = match (ucfirst($this->schedule->entry_point)) {
            'Video' => config('externallinks.video_session_url'),
            'Notary', 'Affidavit' => config('externallinks.verify_session_url'),
            'CFO' => config('externallinks.cfo_session_url'),
        };

        $this->state = User::where('email', strtolower($this->email))->whereNotNull('user_access_code')->whereNotNull('registration_mode')->first() ? true : false;

        $this->link = "{$this->root_domain}?e={$this->email}&f={$this->verify_status}&schedule_id={$this->schedule->id}&document_id={$this->document->id}&entry_point={$this->document->entry_point}&code={$this->code}";

        $this->role = match ($this->participant?->role) {
            'Signer', 'Notary' => '',
            'Viewer' => 'witness',
        };
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            to: [$this->email],
            subject: "You have been invited to {$this->role} a session",
        );
    }

    public function content()
    {
        return new Content(
            markdown: 'emails.schedule.ScheduleSessionParticipant',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
