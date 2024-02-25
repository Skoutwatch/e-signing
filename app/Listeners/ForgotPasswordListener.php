<?php

namespace App\Listeners;

use App\Events\User\ForgotPassword;
use App\Mail\ForgetPasswordEmail;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordListener
{
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
    public function handle(ForgotPassword $event)
    {
        $details = $event->detail;
        $link = $event->link;

        Mail::send(new ForgetPasswordEmail($details, $link)) ? response(['status' => true, 'message' => 'Passwor Reset Email Sent']) : response(['status' => false, 'message' => 'Error Sending Password Reset Email, try later']);
    }
}
