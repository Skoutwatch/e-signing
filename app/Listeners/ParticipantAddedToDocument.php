<?php

namespace App\Listeners;

use App\Events\Document\ParticipantAdded;
use App\Traits\Api\EmailTraits;
use App\Traits\Api\OtpTraits;

class ParticipantAddedToDocument
{
    use EmailTraits, OtpTraits;

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
     * @param  object  $event
     * @return void
     */
    public function handle(ParticipantAdded $event)
    {
        $details = $event->details;

        $maildata = [];

        // $participantname = array();

        foreach ($details as $detail) {
            $otp = $this->generate_otp($detail->email);

            $maildata = [
                'token' => $otp,
                'first_name' => $detail->first_name,
                'last_name' => $detail->last_name,
                'email' => $detail->email,
                'document_id' => $detail->document_id,
                'document_owner' => $detail->document_owner,
                'document_owner_email' => $detail->document_owner_email,
                'document_title' => $detail->title,
                'user_access_code' => $detail->user_access_code,
                'role' => $detail->role,
            ];
        }
    }
}
