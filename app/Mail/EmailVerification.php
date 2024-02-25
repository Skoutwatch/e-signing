<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class EmailVerification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $link;

    public function __construct(public User $user, public int $otp)
    {

        Log::debug($user->role);

        $this->link = match ($user->role) {
            'User' => config('externallinks.user_email_verify_url')."?email={$this->user->email}&access_code={$this->otp}",
            'Notary' => config('externallinks.notary_email_verify_url')."?email={$this->user->email}&access_code={$this->otp}",
            default => config('externallinks.user_email_verify_url')."?email={$this->user->email}&access_code={$this->otp}"
        };

    }

    public function envelope()
    {
        return new Envelope(
            to: [$this->user?->email],
            subject: 'You’re almost there…',
        );
    }

    public function content()
    {
        return new Content(
            markdown: 'emails.user.EmailVerification',
        );
    }
}
