<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TrialUserEmail extends Mailable
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
     * Build the message.resources/views/emails/subscriptions/TrialPlanEmail.blade.php
     *
     * @return $this
     */
    public function build()
    {
        return $this->to($this->detail?->email ? $this->detail?->email : auth('api')->user()->email)
            ->subject('You now have our 14-day unlimited access with ToNote!')
            ->markdown('emails.subscriptions.TrialPlanEmail');
    }
}
