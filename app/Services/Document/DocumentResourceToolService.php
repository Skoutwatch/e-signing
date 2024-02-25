<?php

namespace App\Services\Document;

use App\Events\Document\SignerSigned;
use App\Events\Document\SigningCompleted;
use App\Models\Document;
use App\Models\DocumentResourceTool;
use App\Models\User;

class DocumentResourceToolService
{
    public function isOwnerDocument($user, Document $document)
    {
        return $document->user_id != $user->id ? false : true;
    }

    public function canDeleteTool($user, Document $document)
    {
        return $document->user_id != $user->id ? false : true;
    }

    public function canEditTool($user, Document $document)
    {
        return $document->user_id != $user->id ? false : true;
    }

    public function canCreateTool($user, Document $document)
    {
        return $document->user_id != $user->id ? false : true;
    }

    public function canFillInTool($user, DocumentResourceTool $resourceTool)
    {
        return $resourceTool->participants->where('user_id', $user->id)->first() ? true : false;
    }

    public function iAddedMyselfToDocument($user, Document $document)
    {
        return $document->user_id != $user->id ? false : true;
    }

    public function matchRights()
    {
        $message = match (false) {
            $this->isOwnerDocument => null,
            400 => 'not found',
            500 => 'server error',
            default => 'You dont have rights to this action',
        };
    }

    public function checkIfSignatureIsCompleted(Document $document)
    {
        if ($document->has('tools') && $document->unSignedTools->count() == 0 && $document->participants->count() <= 1) {
            return true;
        }

        if ($document->has('tools') && $document->unSignedTools->count() == 0 && $document->participants->count() > 1) {
            $document->status = 'Completed';
            $document->save();

            return true;

            // foreach($document->participants as $participants){
            //     $this->sendSignedCompletedMail($participants);
            // }
        }

    }

    public function verifyAllSignaturesAreSigned(Document $document)
    {
        return $document->has('tools') && $document->unSignedTools->count() == 0;
    }

    public function checkIfToolIsSigned(DocumentResourceTool $tool)
    {
        if ($tool->append_print_id != '') {
            $this->sendSignerSignMail($tool);
        }

        // $documentResourceTool?->upload?->document ? $this->checkIfSignatureIsCompleted($documentResourceTool?->upload?->document) : null;
    }

    public function userTools(Document $document, User $user)
    {
    }

    public function userSignedTools(Document $document, User $user)
    {
    }

    public function userUnsignedTools(Document $document, User $user)
    {
    }

    public function sendSignedCompletedMail($participants)
    {
        event(new SigningCompleted($participants));
    }

    public function sendSignerSignMail($detail)
    {
        event(new SignerSigned($detail));
    }

    public function processToolAddToolLog($document)
    {
        (new DocumentAuditTrailService(auth('api')->user(), $document))->audit(auth('api')->user()->name." created {$document->title} document");
    }

    public function processToolUpdateToolLog($document)
    {
        (new DocumentAuditTrailService(auth('api')->user(), $document))->audit(auth('api')->user()->name." created {$document->title} document");
    }
}
