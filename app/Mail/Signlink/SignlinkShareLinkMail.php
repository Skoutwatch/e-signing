<?php

namespace App\Mail\Signlink;

use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SignlinkShareLinkMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(public Document $document, string $email)
    {
        //
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'A link document has been shared with you to sign',
        );
    }

    public function content()
    {
        return new Content(
            markdown: 'emails.signlink.SignlinkShareLinkMail',
            with: ['link' => config('externallinks.signlink_public_url')."{$this->document->id}"],
        );
    }
}
