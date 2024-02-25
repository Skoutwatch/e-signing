<?php

namespace App\Http\Controllers\Api\V1\Document;

use App\Enums\DocumentStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Document\DocumentParticipantFormRequest;
use App\Http\Requests\Document\UpdateDocumentParticipantFormRequest;
use App\Models\Document;
use App\Models\DocumentParticipant;
use App\Models\DocumentResourceTool;
use App\Models\User;
use App\Services\Document\DocumentAuditTrailService;
use App\Services\Document\DocumentParticipantService;
use App\Services\Document\DocumentService;
use App\Services\Mixpanel\MixpanelService;
use App\Services\User\UserService;

class DocumentParticipantController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/v1/document-participants",
     *      operationId="postDocumentParticipant",
     *      tags={"DocumentParticipant"},
     *      summary="Post DocumentParticipant",
     *      description="Post DocumentParticipant",
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(ref="#/components/schemas/DocumentParticipantFormRequest")
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
    public function store(DocumentParticipantFormRequest $request)
    {
        $document = Document::find($request['participants'][0]['document_id']);

        $document->update($request->only('has_sequence_order'));

        $document->update($request->only('has_reminder'));

        $incorrectDetails = false;

        $participants = (new DocumentParticipantService())->returnUniqueParticipants($request['participants'], 'email');

        (new DocumentParticipantService())->checkParticipantsLimits($document, $request['participants']);

        foreach ($participants as $participant) {

            $user = (new UserService())->createOrFindUserIfExist($participant, 'documents');

            $incorrectDetails = $user->first_name != $participant['first_name'] || $user->last_name != $participant['last_name'] ? true : false;

            $document->participants->where('user_id', $user->id)->first() ?? (new DocumentParticipantService())->addParticipant($document, $participant, $user);
        }

        $message = $incorrectDetails ? 'Email input does not match name. This has been corrected.' : 'Participant added sucessfully';

        ($document->status === DocumentStatus::Sent && $document->has_sequence_order === 0) ? (new DocumentParticipantService())->documentParticipantResetMail($document) : null;

        (new MixpanelService())->participantsAdded($participants, $document);

        return $this->showOneWithMessage((new DocumentService())->userDocumentByParentId($request['participants'][0]['document_id']), $message);
    }

    /**
     * @OA\Put(
     *      path="/api/v1/document-participants/{id}",
     *      operationId="updateDocumentParticipants",
     *      tags={"DocumentParticipant"},
     *      summary="Update DocumentParticipants",
     *      description="Update DocumentParticipants",
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="DocumentParticipants ID",
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
     *          @OA\JsonContent(ref="#/components/schemas/UpdateDocumentParticipantFormRequest")
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
    public function update(UpdateDocumentParticipantFormRequest $request, $id)
    {
        $document = Document::find($request['participants'][0]['document_id']);

        $document->update($request->only('has_sequence_order'));
        $document->update($request->only('has_reminder'));

        $incorrectDetails = false;

        foreach ($request['participants'] as $participant) {
            $member = (new DocumentParticipantService())->findParticipant($participant['document_participant_id']);

            $user = $member->user;

            $emailsAreSame = $member?->user?->email == $participant['email'] ? true : false;

            $participantUserTryingToChangeAlreadyExist = ($emailsAreSame == false) ? User::where('email', $participant['email'])->first() : null;

            if ($participantUserTryingToChangeAlreadyExist == null && $user->national_verification != true) {
                $user->update([
                    'first_name' => $participant['first_name'],
                    'last_name' => $participant['last_name'],
                    'email' => $participant['email'],
                ]);
            }

            $changingUser = $emailsAreSame ? $user : $participantUserTryingToChangeAlreadyExist;

            $incorrectDetails = $user->first_name != $participant['first_name'] || $user->last_name != $participant['last_name'] ? true : false;

            (new DocumentParticipantService())->updateParticipant($document, $participant, $changingUser);
        }

        $message = $incorrectDetails ? 'Email input does not match name. This has been corrected.' : 'Participants have been updated sucessfully';

        return $this->showOneWithMessage((new DocumentService())->userDocumentByParentId($request['participants'][0]['document_id']), $message);
    }

    /**
     * @OA\Delete(
     *      path="/api/v1/document-participants/{id}",
     *      operationId="deleteDocumentParticipant",
     *      tags={"DocumentParticipant"},
     *      summary="Delete DocumentParticipant",
     *      description="Delete DocumentParticipant",
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="DocumentParticipant ID",
     *          required=true,
     *          in="path",
     *
     *          @OA\Schema(
     *              type="string"
     *          )
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
    public function destroy($id)
    {
        $participant = DocumentParticipant::find($id);

        $documentId = $participant?->document?->id;

        $documentUpload = $participant?->document->uploads->pluck('id')->toArray();

        $userId = $participant?->user?->id;

        $tools = DocumentResourceTool::where('user_id', $userId)->whereIn('document_upload_id', $documentUpload)->get();

        foreach ($tools as $tool) {
            $tool->delete();
        }

        $participant->delete() ? null : (throw new \ErrorException('Cannot delete participant'));

        (new DocumentAuditTrailService(auth('api')->user(), $participant?->document))->removeParticipantAuditTrail($participant?->document?->entry_point, $participant?->user);

        return $this->showOne((new DocumentService())->userDocumentByParentId($documentId));
    }
}
