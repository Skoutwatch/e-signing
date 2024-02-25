<?php

namespace App\Traits\Api;

use Illuminate\Support\Facades\Mail;

trait EmailTraits
{
    protected function sendWelcomeEmail($email, $data)
    {
        Mail::to($email)->send(new \App\Mail\WelcomeEmail($data));

        return response(['status' => true, 'message' => 'Email Sent']);
    }

    protected function sendJoinCommunityEmail($email, $data)
    {
        Mail::to($email)->send(new \App\Mail\JoinCommunity($data));

        return response(['status' => true, 'message' => 'Email Sent']);
    }

    protected function sendNotaryWelcomeEmail($email, $data)
    {
        Mail::to($email)->send(new \App\Mail\NotaryWelcomeEmail($data));

        return response(['status' => true, 'message' => 'Email Sent']);
    }

    protected function sendBussinessSignupEmail($email, $data)
    {
        Mail::to($email)->send(new \App\Mail\SignUpBusinessEmail($data));

        return response(['status' => true, 'message' => 'Email Sent']);
    }

    protected function sendNotaryPasswordResetEmail($email, $data)
    {
        Mail::to($email)->send(new \App\Mail\NotaryResetPasswordEmail($data));

        return response(['status' => true, 'message' => 'Email Sent']);
    }

    protected function sendSuccessfulBillingEmail($email, $data)
    {
        Mail::to($email)->send(new \App\Mail\SuccessfulBilling($data));

        return response(['status' => true, 'message' => 'Email Sent']);
    }

    protected function sendUnsuccessfulBillingEmail($email, $data)
    {
        Mail::to($email)->send(new \App\Mail\UnsuccessfulBilling($data));

        return response(['status' => true, 'message' => 'Email Sent']);
    }

    protected function sendResetSubscriptionEmail($email, $data)
    {
        Mail::to($email)->send(new \App\Mail\ResetSubscription($data));

        return response(['status' => true, 'message' => 'Email Sent']);
    }

    protected function sendCardExpirationEmail($email, $data)
    {
        Mail::to($email)->send(new \App\Mail\CardExpiration($data));

        return response(['status' => true, 'message' => 'Email Sent']);
    }

    protected function sendReInstateEmail($email, $data)
    {
        Mail::to($email)->send(new \App\Mail\ReInstateMember($data));

        return response(['status' => true, 'message' => 'Email Sent']);
    }

    protected function sendSubscriptionRenewerEmail($email, $data)
    {
        Mail::to($email)->send(new \App\Mail\SubscriptionRenewer($data));

        return response(['status' => true, 'message' => 'Email Sent']);
    }

    protected function sendAdminInviteEmail($email, $data)
    {
        Mail::to($email)->send(new \App\Mail\AdminInvite($data));

        return response(['status' => true, 'message' => 'Email Sent']);
    }

    protected function sendAdminResetPasswordEmail($email, $data)
    {
        Mail::to($email)->send(new \App\Mail\AdminResetPassword($data));

        return response(['status' => true, 'message' => 'Email Sent']);
    }

    protected function sendContactSalesEmail($email, $data)
    {
        Mail::to($email)->send(new \App\Mail\ContactSales($data));

        return response(['status' => true, 'message' => 'Email Sent']);
    }

    protected function sendNotaryScheduleEmail($email, $data)
    {
        Mail::to($email)->send(new \App\Mail\NotarySchedule($data));

        return response(['status' => true, 'message' => 'Email Sent']);
    }

    protected function sendNotaryInvitationEmail($email, $data)
    {
        Mail::to($email)->send(new \App\Mail\NotaryInvitation($data));

        return response(['status' => true, 'message' => 'Email Sent']);
    }

    protected function sendNotaryInvitationThirdPartyEmail($email, $data)
    {
        Mail::to($email)->send(new \App\Mail\NotaryInvitationThirdParty($data));

        return response(['status' => true, 'message' => 'Email Sent']);
    }

    protected function sendNotaryNotificationEmail($email, $data)
    {
        Mail::to($email)->send(new \App\Mail\NotaryNotification($data));

        return response(['status' => true, 'message' => 'Email Sent']);
    }
}
