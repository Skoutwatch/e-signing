<?php

namespace App\Mail\Partner;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserFirstTransactionMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Transaction $transaction, public User $partner)
    {
        $this->partner = $partner;
        $this->transaction = $transaction;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            to: ['mosesdaniel@skynedconsults.com', 'ask@gettonote.com'],
            subject: 'Someone has used the referral code: {$this->transaction->referal_code}',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.partner.referralCodeUsed',
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
