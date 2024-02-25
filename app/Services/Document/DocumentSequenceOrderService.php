<?php

namespace App\Services\Document;

use App\Events\Document\DocumentOwnerActionMailEvent;
use App\Events\Document\DocumentParticipantSendMailEvent;
use App\Models\Document;

class DocumentSequenceOrderService
{
    public function __construct(public Document $document)
    {
    }

    public function nextDocumentParticipantAction()
    {
        $nextDocumentParticipantAction = $this->document->participants
            ->where('role', '!=', 'Viewer')
            ->whereIn('status', ['Sent', null])
            ->where('notification_count', '<', 1)
            ->sortBy('sequence_order')
            ->first();

        if ($nextDocumentParticipantAction === null) {
            return null;
        }

        return $nextDocumentParticipantAction;
    }

    public function documentParticipantSendMail($participant, $status = 'Sent')
    {
        event(new DocumentParticipantSendMailEvent($this->document, $participant));

        $participant->update([
            'notification_count' => (int) $participant->notification_count + 1,
            'status' => $status,
        ]);
    }

    public function sendMailViaSequenceCommand()
    {
        $res = match ($this->document->has_sequence_order) {
            true, 1 => $this->initiateNextDocumentParticipantAction(),
            false, 0 => $this->processMailActivitiesToAllUsers(),
            default => $this->processMailActivitiesToAllUsers(),
        };

        $this->notifyDocumentOwnerDocumentSentToParticipants();
    }

    public function initiateNextDocumentParticipantAction()
    {
        $participant = $this->nextDocumentParticipantAction();

        $participant ? $this->documentParticipantSendMail($participant) : null;
    }

    public function processMailActivitiesToAllUsers()
    {
        foreach ($this->document->participants as $participant) {
            ($participant?->notification_count < 1) ? $this->documentParticipantSendMail($participant) : null;
        }
    }

    public function notifyDocumentOwnerDocumentSentToParticipants()
    {
        event(new DocumentOwnerActionMailEvent($this->document));
    }

    public function sendToViewers()
    {
        $sendMailToViewers = $this->document->participants->where('role', 'Viewer');

        foreach ($sendMailToViewers as $viewers) {
            ($viewers?->notification_count == 0) ? $this->documentParticipantSendMail($viewers) : null;
        }
    }

    public function checkIfOwnerIsAParticipantAndHasSignedHisSignatureBeforeSending()
    {
        $ownerIsAParticipant = $this->document->participants->where('user_id', auth()->id())->first();

        if ($ownerIsAParticipant == null) {
            return;
        }

        ($this->document->userunsigned->count() <= 0) ?
            $ownerIsAParticipant->update([
                'notification_count' => 2,
                'status' => 'Signed',
            ]) : null;
    }
}
