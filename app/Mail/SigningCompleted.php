<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SigningCompleted extends Mailable
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
        $link = config('externallinks.document_view_url').'/'.$this->detail['id'];

        return $this->to($this->detail?->email)->subject('Your document is signed and complete')->markdown('emails.document.DocumentSignedEmail')->with('link', $link);
    }
}
