<?php

namespace App\Mail\Schedule;

use App\Models\Document;
use App\Models\DocumentParticipant;
use App\Models\ScheduleSession;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AddedAsViewerEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(
        public Document $document,
        public DocumentParticipant $participant,
        public ScheduleSession $schedule
    ) {
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $verify_status = $this->participant?->user?->isset_password ? '0' : '1';

        $email = $this->participant?->email;

        $root_domain = match (ucfirst($this->schedule->entry_point)) {
            'Video' => config('externallinks.video_session_url'),
            'Notary', 'Affidavit' => config('externallinks.verify_session_url'),
            'CFO' => config('externallinks.cfo_session_url'),
        };

        $role = match ($this->participant?->role) {
            'Signer' => '',
            'Viewer' => 'witness',
        };

        $policy_link = config('externallinks.tonote_policy_public_url');

        $code = $this->participant?->user?->user_access_code;

        $link = "$root_domain?e=$email&f=$verify_status&schedule_id={$this->schedule->id}&document_id={$this->document->id}&entry_point={$this->document->entry_point}&code=$code";

        if ($state) {
            return $this->to($this->participant?->email)
                ->subject('You have been invited to witness a session')
                ->markdown('emails.notary.NotaryInvitationViewerThirdParty')
                ->with('link', $link);
        } else {
            return $this->to($this->participant?->email)
                ->subject('You have been invited to witness a session')
                ->markdown('emails.notary.NotaryInvitationThirdPartyViewerExistingUser')
                ->with('link', $link);
        }
    }
}
