<?php

namespace App\Mail\Affiliate;

use App\Models\Affiliate;
use App\Services\Affiliate\AffiliateService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AffiliateRegisteredMail extends Mailable
{
    use Queueable, SerializesModels;

    public AffiliateService $service;

    /**
     * Create a new message instance.
     */
    public function __construct(public Affiliate $affiliate, public object $otp, public bool $new)
    {
        $this->service = new AffiliateService();
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            to: $this->affiliate->user->email,
            subject: 'Welcome to our Affiliate Programme',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {

        $link = config('externallinks.user_email_verify_url')."?email={$this->affiliate->user->email}&access_code=".$this->otp->token;

        return new Content(
            markdown: 'emails.affiliate.registered',
            with: [
                'link' => $link,
            ],
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
}
