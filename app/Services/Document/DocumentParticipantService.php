<?php

namespace App\Services\Document;

use App\Enums\DocumentParticipantStatus;
use App\Enums\DocumentStatus;
use App\Events\Participant\AddSingleParticipantEvent;
use App\Models\DocumentParticipant;
use App\Services\Subscription\SubscriptionRestrictionService;
use App\Traits\Api\OtpTraits;

class DocumentParticipantService
{
    use OtpTraits;

    public function addParticipant($document, $participant, $user)
    {
        $role = $participant['role'];

        $token = $this->generate_otp($user->email);

        $documentParticipant = $document->participants()->create([
            'user_id' => $user->id,
            'who_added_id' => auth('api')->id(),
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'phone' => $user->phone,
            'role' => $participant['role'],
            'comment' => array_key_exists('comment', $participant) ? $participant['comment'] : null,
            'sequence_order' => array_key_exists('sequence_order', $participant) ? $participant['sequence_order'] : null,
            'entry_point' => array_key_exists('entry_point', $participant) ? $participant['entry_point'] : 'Docs',
            'otp' => $token->token,
        ]);

        $document->touch();

        $sendNotification = array_key_exists('message', $participant);

        $notify = $sendNotification ? $participant['message'] : null;

        $notify ?? event(new AddSingleParticipantEvent($documentParticipant));

        (new DocumentAuditTrailService(auth('api')->user(), $document))->addParticipantAuditTrail($document['entry_point'], $documentParticipant, $role);

        return $documentParticipant;
    }

    public function updateParticipant($document, $participant, $user)
    {
        $member = $this->findParticipant($participant['document_participant_id']);

        $member->update([
            'user_id' => $user->id,
            'who_added_id' => auth('api')->id(),
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'phone' => $user->phone,
            'role' => $participant['role'],
            'sequence_order' => array_key_exists('sequence_order', $participant) ? $participant['sequence_order'] : null,
            'entry_point' => array_key_exists('entry_point', $participant) ? $participant['entry_point'] : 'Docs',
            'notification_count' => 0,
        ]);

        $document->touch();
    }

    public function findParticipant($document_particpant_id)
    {
        return DocumentParticipant::find($document_particpant_id);
    }

    public function returnUniqueParticipants($array, $property)
    {
        $tempArray = array_unique(array_column($array, $property));
        $moreUniqueArray = array_values(array_intersect_key($array, $tempArray));

        return $moreUniqueArray;
    }

    public function checkHowManyTimeAParticipantEmailCounts($document, $user)
    {
        $document->participants->where('email', $user['email'])->count();
    }

    public function transferToolsToUserIfEmailRepeats($document, $user)
    {
        $tools = $document->tools->where('email', $user['email']);
    }

    public function deleteParticipantEmailIfAppearedMoreThanOnce($document, $user)
    {
        $document->participants->where('email', $user->email)->having('occurences', '>', 1)->get();
    }

    public function deleteDocumentParticipantIfAppearedMoreThanOnce($document, $user)
    {
        $document->participants->where('user_id', $user->id)->delete();
    }

    public function checkParticipantsLimits($document, array $participants): bool
    {
        $documentEmails = $document->participants->pluck('email')->toArray();

        $incomingDocumentParticipants = collect($participants)->pluck('email')->toArray();

        $allEmails = array_unique(array_merge($documentEmails, $incomingDocumentParticipants));

        $removeDocumentOwner = array_filter($allEmails, function ($e) use ($document) {
            return $e !== $document?->user?->email;
        });

        $countAllEmailAttempts = count($removeDocumentOwner);

        (new SubscriptionRestrictionService())->checkDocumentParticipantRestrictions('Number of Participants', $countAllEmailAttempts);

        return true;
    }

    public function documentParticipantResetMail($document)
    {
        $document->update(['status' => DocumentStatus::New]);

        foreach ($document->participants as $participant) {
            $participant->update([
                'notification_count' => 0,
                'status' => DocumentParticipantStatus::Empty,
            ]);
        }
    }
}
