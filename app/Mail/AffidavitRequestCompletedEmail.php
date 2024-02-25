<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AffidavitRequestCompletedEmail extends Mailable
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

        if ($this->detail?->sessionSchedule?->type == 'Request Affidavit') {
            return $this->to($this->detail?->user?->email)->subject('Your document request is complete')->markdown('emails.document.DocumentAffidavitRequestCompleted')->with('link', $link);
        } else {
            return $this->to($this->detail?->user?->email)->subject('Your document Request is complete')->markdown('emails.document.DocumentRequestCompleted')->with('link', $link);
        }
    }
}
