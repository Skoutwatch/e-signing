<?php

namespace App\Http\Controllers\Api\V1\Document;

use App\Models\Document;
use App\Enums\DocumentStatus;
use App\Services\User\UserService;
use App\Models\DocumentParticipant;
use App\Http\Controllers\Controller;
use App\Services\Document\DocumentService;
use App\Services\Mixpanel\MixpanelService;
use App\Services\Document\DocumentConversionService;
use App\Services\Document\DocumentParticipantService;
use App\Services\Document\DocumentSequenceOrderService;
use App\Services\Subscription\SubscriptionRestrictionService;
use App\Http\Requests\Document\DocumentParticipantSendMailFormRequest;

class DocumentParticipantSendMailController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/v1/document-participants-send-email",
     *      operationId="postDocumentParticipantEmail",
     *      tags={"DocumentParticipant"},
     *      summary="Post DocumentParticipant email notification",
     *      description="Post DocumentParticipant email notification",
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(ref="#/components/schemas/DocumentParticipantSendMailFormRequest")
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
    public function store(DocumentParticipantSendMailFormRequest $request)
    {
        (new SubscriptionRestrictionService())->checkRestrictions('Number of Envelops');

        $message = $request['message'] ? $request['message'] : null;

        $document = Document::find($request['participants'][0]['document_id']);

        $document->update($request->only('has_sequence_order'));

        $request['files'] ? (new DocumentConversionService())->collectAllRequest($request->only('files'), $document, 'Shared') : null;

        $participants = (new DocumentParticipantService())->returnUniqueParticipants($request['participants'], 'email');

        (new DocumentParticipantService())->checkParticipantsLimits($document, $request['participants']);

        foreach ($participants as $participant) {

            $user = (new UserService())->findUserRegistrationOutsideAuth($participant, 'documents');

            $checkParticipant = DocumentParticipant::where('document_id', $participant['document_id'])->where('user_id', $user->id)->first();

            $checkParticipant == null ? (new DocumentParticipantService())->addParticipant($document, $participant, $user) : null;
        }

        $document->update([
            'status' => DocumentStatus::Sent,
            'message' => $message,
        ]);

        (new MixpanelService())->documentSent($participant);

        (new DocumentSequenceOrderService($document))->checkIfOwnerIsAParticipantAndHasSignedHisSignatureBeforeSending();

        (new DocumentSequenceOrderService($document))->sendMailViaSequenceCommand();

        ($document->has_sequence_order == 1) ? (new DocumentSequenceOrderService($document))->sendToViewers() : null;

        (new SubscriptionRestrictionService())->consumeFeatureCreate(auth('api')->user()?->activeTeam?->team, 'Number of Envelops');

        return $this->showOne((new DocumentService())->userDocumentByParentId($request['participants'][0]['document_id']));
    }
}
