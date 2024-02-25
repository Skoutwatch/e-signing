<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotaryInvitationThirdParty extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $detail;

    public function __construct($detail)
    {
        $this->detail = $detail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $link = config('externallinks.tonote_policy_public_url');
        $joinLink = env('FRONTEND_USER_URL')."/{$this->detail?->session?->id}";

        return $this->subject('You have been invited to a virtual session…')->markdown('emails.notary.NotaryInvitationThirdPartyExistingUser')
            ->with(['link' => $link, 'join' => $joinLink]);
    }
}
