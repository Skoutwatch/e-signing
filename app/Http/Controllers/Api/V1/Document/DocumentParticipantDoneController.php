<?php

namespace App\Http\Controllers\Api\V1\Document;

use App\Enums\DocumentParticipantRole;
use App\Enums\DocumentParticipantStatus;
use App\Enums\DocumentStatus;
use App\Enums\EntryPoint;
use App\Events\Document\DocumentCompletedEvent;
use App\Events\Document\DocumentOwnerActionMailEvent;
use App\Events\Document\DocumentOwnerParticipantActionEvent;
use App\Events\Document\DocumentParticipantActionEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Document\StoreDocumentParticipantDoneFormRequest;
use App\Models\Document;
use App\Services\Document\DocumentAuditTrailService;
use App\Services\Document\DocumentSequenceOrderService;
use App\Services\Document\DocumentService;
use App\Services\Subscription\SubscriptionRestrictionService;
use ErrorException;

class DocumentParticipantDoneController extends Controller
{
    /**
     * @OA\Put(
     *      path="/api/v1/document-participants-done/{id}",
     *      operationId="updateParticipantDone",
     *      tags={"DocumentParticipant"},
     *      summary="Update DocumentParticipantDone",
     *      description="Update DocumentParticipantDone",
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="Document ID",
     *          required=true,
     *          in="path",
     *
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(ref="#/components/schemas/StoreDocumentParticipantDoneFormRequest")
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="Successful signin",
     *
     *          @OA\MediaType(
     *             mediaType="application/json",
     *         ),
     *       ),
     *
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      security={ {"bearerAuth": {}} },
     * )
     */
    public function update(StoreDocumentParticipantDoneFormRequest $request, $id)
    {
        $document = Document::find($id);

        $user = auth('api')->user();

        $status = $request['status'];

        $signatureLeft = $document->userunsigned->count();

        $currentUserParticipantAction = $document->participants->where('user_id', $user->id)?->first();

        $anyParticipantThatDeclined = $document->participants()->where('status', DocumentParticipantStatus::Declined)->count();

        $anyParticipantsThatHasNotSigned = $document->participants
            ->where('user_id', '!=', $user->id)
            ->where('role', '!=', DocumentParticipantRole::Viewer)
            ->whereIn('status', [
                DocumentParticipantStatus::Empty,
                DocumentParticipantStatus::Sent,
                DocumentParticipantStatus::Nothing,
            ])->count();

        ($currentUserParticipantAction === null) ? (throw new ErrorException('Invalid participant action')) : null;

        $owner = $document->user_id == $user->id;

        match ($currentUserParticipantAction->status) {
            DocumentParticipantStatus::Sent,
            DocumentParticipantStatus::Nothing => null,

            DocumentParticipantStatus::Signed,
            DocumentParticipantStatus::Approved,
            DocumentParticipantStatus::Declined, => (throw new ErrorException("You have already {$currentUserParticipantAction->status} this document")),

            default => null,
        };

        match ($document->status) {
            'Sent' => null,
            'Signed' => (throw new ErrorException('You have already signed this document')),
            'Approved' => (throw new ErrorException('You have already approved this document')),
            'Declined' => (throw new ErrorException('You are not allowed to sign as the document was rejected.')),
            'Completed' => (throw new ErrorException('This document has already been completed')),
            default => null,
        };

        if ($document->entry_point == EntryPoint::Docs && $owner) {
            (new SubscriptionRestrictionService())->checkEnvelopsRestrictions('Number of Envelops');
        }

        if (($signatureLeft > 0) && ($status != DocumentParticipantStatus::Declined)) {
            return $this->errorResponse("You are yet to sign $signatureLeft annotation(s) assigned to you", 409);
        }

        $currentUserParticipantAction->status == $status ? (throw new ErrorException("You have already $status this document")) : null;

        $currentUserParticipantAction->update([
            'status' => $status,
            'comment' => $request['comment'],
            'notification_count' => 2,
        ]);

        ($status === DocumentParticipantStatus::Declined || $status === DocumentParticipantStatus::Approved)
            ? (new DocumentAuditTrailService($user, $document))->participantActionDocumentAuditTrail($status, $request['comment'])
            : null;

        $prepareDocumentStatus = match (true) {
            ($status === DocumentParticipantStatus::Signed ||
            $status === DocumentParticipantStatus::Approved) &&
            $document->unsigned->count() === 0 &&
            $anyParticipantThatDeclined <= 0 &&
            $anyParticipantsThatHasNotSigned <= 0 => DocumentStatus::Completed,

            ($status == DocumentParticipantStatus::Signed) && $anyParticipantThatDeclined > 0 => DocumentStatus::Declined,

            default => $status,
        };

        if ($prepareDocumentStatus == DocumentStatus::Completed) {

            (new DocumentAuditTrailService($user, $document))->completeDocumentAuditTrail();

            (new DocumentService())->processDocument($id);

            $document->update(['status' => DocumentStatus::Completed]);

            $this->documentCompleteEventProcess($document);

            event(new DocumentOwnerParticipantActionEvent($currentUserParticipantAction));

            event(new DocumentOwnerActionMailEvent($document));

        } elseif ($prepareDocumentStatus == DocumentStatus::Declined) {

            $document->update(['status' => DocumentStatus::Declined]);

            event(new DocumentOwnerParticipantActionEvent($currentUserParticipantAction));

        } else {

            event(new DocumentOwnerParticipantActionEvent($currentUserParticipantAction));

            (new DocumentSequenceOrderService($document))->sendMailViaSequenceCommand();
        }

        return match ($prepareDocumentStatus) {
            'Approved' => $this->showMessage('This document is now approved'),
            'Declined' => $this->showMessage('This document is now declined'),
            'Completed' => $this->showMessage('This document is now complete'),
            'Signed' => $this->showOne((new DocumentService())->userDocumentById($document->id)),
            default => $this->showOne((new DocumentService())->userDocumentById($document->id)),
        };
    }

    public function documentCompleteEventProcess($document)
    {
        foreach ($document->participants as $participant) {
            event(new DocumentCompletedEvent($document, $document?->completedDocument, $participant));
        }
    }

    public function notifyParticipantsASignerHasSigned($document, $currentActionParticipant)
    {
        foreach ($document->participants->where('status', '!=', null) as $sentEmailToparticipant) {
            event(new DocumentParticipantActionEvent($document, $sentEmailToparticipant, $currentActionParticipant));
        }
    }
}
