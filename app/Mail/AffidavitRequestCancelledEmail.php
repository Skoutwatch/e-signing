<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AffidavitRequestCancelledEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $detail;

    public $session;

    public function __construct($detail, $session)
    {
        $this->detail = $detail;
        $this->session = $session;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $link = config('externallinks.document_view_url').'/'.$this->detail['id'];

        return $this->to($this->detail?->user?->email)->subject('Your Document Request has Been Cancelled')->markdown('emails.document.DocumentRequestCancelled')->with('link', $link);
    }
}
