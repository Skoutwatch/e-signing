<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AffidavitRequestInviewEmail extends Mailable
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

        return $this->to($this->detail?->user?->email)->subject('Your Document Request has An Update')->markdown('emails.document.DocumentRequestInview')->with('link', $link);
    }
}
