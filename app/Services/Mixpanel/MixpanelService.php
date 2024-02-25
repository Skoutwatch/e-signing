<?php

namespace App\Services\Mixpanel;

use Mixpanel;

class MixpanelService
{
    public function participantsAdded($participant, $document)
    {
        $mixpanel = Mixpanel::getInstance(config('services.mixpanel.token'));

        // Track Mixpanel event for each participant added
        $mixpanel->track('Participant Added', [
            'document_id' => $document->id,
            'participants_added' => count($participant),
        ]);
    }

    public function documentUploaded($combined_file, $document)
    {
        $mixpanel = Mixpanel::getInstance(config('services.mixpanel.token'));

        $mixpanel->track('Document Uploaded', [
            'uploaded_document' => count($combined_file),
            'document_owner' => $document->user->first_name,
        ]);
    }

    public function documentSent($participant)
    {
        $mixpanel = Mixpanel::getInstance(config('services.mixpanel.token'));

        $mixpanel->track('Document sent', [
            'document_id' => $participant['document_id'],
            'status' => 'sent',
        ]);
    }

    public function teamMembersAdded($team)
    {
        $mixpanel = Mixpanel::getInstance(config('services.mixpanel.token'));

        $mixpanel->track('Team members Added', [
            'team_id' => auth('api')->user()->activeTeam?->team?->id,
            'teamMember_added' => count($team),
        ]);
    }

    public function googleUserLogin($user)
    {
        $mixpanel = Mixpanel::getInstance(config('services.mixpanel.token'));

        $mixpanel->track('User login', [
            'user_name' => $user->first_name.' '.$user->last_name,
            'login_time' => $user->last_login_activity,
        ]);
    }

    public function userLogin($user)
    {
        $mixpanel = Mixpanel::getInstance(config('services.mixpanel.token'));

        $mixpanel->track('User login', [
            'user_name' => $user->first_name.' '.$user->last_name,
            'login_time' => $user->last_login_activity,
        ]);
    }
}
