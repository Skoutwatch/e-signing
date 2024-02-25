<?php

namespace App\Mail\Schedule;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ScheduleResendAuthOtpEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $particpant;

    public function __construct($particpant)
    {
        $this->particpant = $particpant;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->to($this->particpant?->email)->subject('Session Authentication')->markdown('emails.notary.SessionResendAuthOtpEmail');
    }
}
