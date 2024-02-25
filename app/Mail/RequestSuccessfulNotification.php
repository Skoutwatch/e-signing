<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RequestSuccessfulNotification extends Mailable
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
        $link = config('externallinks.calendly_url');

        return $this->to($this->detail?->user?->email ? $this->detail?->user?->email : auth('api')->user()->email)
            ->subject('We are processing your request…')
            ->markdown('emails.user.RequestSuccessful')
            ->with('link', $link);
    }
}
