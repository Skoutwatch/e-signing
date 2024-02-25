<?php

namespace App\Listeners;

use App\Events\User\EmailVerificationWithLink;
use App\Traits\Api\OtpTraits;
use Illuminate\Support\Facades\Mail;

class SendEmailVerificationWithLink
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

    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle(EmailVerificationWithLink $event)
    {
        $detail = $event->detail;
        $link = $event->link;

        $token = $this->generate_otp($detail['email']);

        return Mail::send(new \App\Mail\EmailVerificationWithLink(['otp' => $token->token, 'email' => $detail['email']], $link)) ? response(['status' => true, 'message' => 'Email Sent']) : response(['status' => false, 'message' => 'Error Sending Verification Email, try later']);
    }
}
