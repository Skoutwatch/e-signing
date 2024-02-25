<?php

namespace App\Listeners;

use App\Mail\EmailVerification;
use App\Traits\Api\OtpTraits;
use Illuminate\Support\Facades\Mail;

class SendEmailVerificationEmail
{
    use OtpTraits;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function handle($event)
    {
        $otp = $event->user ? $this->generate_otp($event->user?->email) : null;

        return Mail::send(new EmailVerification($event->user, $otp->token));
    }
}
