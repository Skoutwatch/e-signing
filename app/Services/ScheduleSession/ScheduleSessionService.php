<?php

namespace App\Services\ScheduleSession;

use App\Events\Schedule\ScheduleParticipantEvent;
use App\Events\Subscription\PaymentConfirmation;
use App\Models\Document;
use App\Models\ScheduleSession;
use App\Models\Transaction;
use App\Services\Document\DocumentAuditTrailService;

class ScheduleSessionService
{
    private $transaction;

    public function setTransactionModel(Transaction $transaction): ScheduleSessionService
    {
        $this->transaction = $transaction;

        return $this;
    }

    public function sendEmail()
    {
        event(new PaymentConfirmation($this->transaction));
    }

    public function processScheduleSession()
    {
        $session = $this->findScheduleSession();

        $session->transactions->count() == 1 ? $session->update(['status' => 'Awaiting']) : null;

        event(new ScheduleParticipantEvent(Document::find($session?->schedule?->id), $session));

        return $this->sendEmail();
    }

    public function findScheduleSession(): ScheduleSession
    {
        return ScheduleSession::find($this->transaction->transactionable_id);
    }

    public function find($id)
    {
        return ScheduleSession::where('id', $id)->first();
    }

    public function ifDocumentIsRequestANotary(): bool
    {
        return $this->findScheduleSession()?->schedule?->type == 'Request A Notary' || $this->findScheduleSession()?->schedule?->type == 'Request Affidavit'
            ? Document::find($this->findScheduleSession()?->schedule?->id)->update(['status', 'Awaiting'])
            : false;
    }

    public function auditStartSessionLog($document, $user)
    {
        (new DocumentAuditTrailService(auth('api')->user(), $document))->audit("{$user->name} scheduled a notary session");
    }

    public function auditUpdateSessionLog($document, $user)
    {
        (new DocumentAuditTrailService(auth('api')->user(), $document))->audit("{$user->name} scheduled a notary session");
    }
}
