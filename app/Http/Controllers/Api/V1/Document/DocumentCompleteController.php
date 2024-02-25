<?php

namespace App\Http\Controllers\Api\V1\Document;

use App\Enums\DocumentStatus;
use App\Events\Document\DocumentCompletedEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Document\UpdateCompleteDocumentFormRequest;
use App\Models\Document;
use App\Models\DocumentParticipant;
use App\Models\ScheduleSession;
use App\Services\Document\DocumentAuditTrailService;
use App\Services\Document\DocumentService;

class DocumentCompleteController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/document-complete",
     *      operationId="allCompletedDocuments",
     *      tags={"Documents"},
     *      summary="allCompletedDocuments",
     *      description="allCompletedDocuments",
     *
     *      @OA\Response(
     *          response=200,
     *          description="Successful signin",
     *
     *          @OA\MediaType(
     *             mediaType="application/json",
     *          ),
     *       ),
     *
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      security={ {"bearerAuth": {}} },
     * )
     */
    public function index()
    {
        $userDocsAsParticipants = DocumentParticipant::where('user_id', auth()->id())
            ->pluck('document_id')
            ->toArray();

        $userDocsAsOwner = DocumentParticipant::where('who_added_id', auth()->id())
            ->pluck('document_id')
            ->toArray();

        $allDocs = array_merge($userDocsAsParticipants, $userDocsAsOwner);

        $documents = Document::whereIn('id', $allDocs)->where('status', DocumentStatus::Completed)->latest('updated_at')->get();

        return $this->showAll($documents);
    }

    /**
     * @OA\Get(
     *      path="/api/v1/document-complete/{id}",
     *      operationId="completeDocument",
     *      tags={"Documents"},
     *      summary="Complete Document",
     *      description="Complete Document",
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
    public function show($id)
    {
        $document = Document::findOrFail($id);

        (new DocumentService())->processDocument($id);

        $user = auth('api')->user();

        if ($document->unsigned->count() > 0) {
            return $this->errorResponse('Sorry!!! Not all tools have been signed on this document completely', 409);
        }

        // if ($document->status == 'Completed') {
        //     return $this->errorResponse('The document has already been signed', 409);
        // }

        $document->update(['status' => 'Completed']);

        $session = $document->scheduleSession ? ScheduleSession::where('schedule_id', $document->scheduleSession->id)->first() : null;

        $session ? $session->update(['status' => DocumentStatus::Completed]) : null;

        if (request()->get('share') == 1 || request()->get('share') == null) {

            $findTheLastCompletedDocument = (new DocumentService())->getCompletedDocument($id);

            foreach ($document->participants as $participant) {
                event(new DocumentCompletedEvent($document, $findTheLastCompletedDocument, $participant));
            }
        }

        (new DocumentAuditTrailService($user, $document))->completeDocumentAuditTrail();

        return match ($document->entry_point) {
            'Docs' => $this->showMessage('This document is now complete'),
            'Notary', 'Affidavit', 'Video', 'CFO' => $this->showMessage('This session is now complete'),
        };
    }

    /**
     * @OA\Put(
     *      path="/api/v1/document-complete/{id}",
     *      operationId="updatecompleteDocument",
     *      tags={"Documents"},
     *      summary="updatecompleteDocument",
     *      description="updatecompleteDocument",
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
     *      ),
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(ref="#/components/schemas/UpdateCompleteDocumentFormRequest")
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
    public function update(UpdateCompleteDocumentFormRequest $request, $id)
    {
        $document = Document::findOrFail($id);

        $user = auth('api')->user();

        if ($document->unsigned->count() > 0) {
            return $this->errorResponse('Sorry!!! Not all tools have been signed on this document completely', 409);
        }

        // if ($document->status == 'Completed') {
        //     return $this->errorResponse('The document has already been signed', 409);
        // }

        $document->update(['status' => 'Completed']);

        $session = $document->scheduleSession ? ScheduleSession::where('schedule_id', $document->scheduleSession->id)->first() : null;

        $session ? $session->update(['status' => DocumentStatus::Completed]) : null;

        if (request()->get('share') == 1 || request()->get('share') == null) {

            $findTheLastCompletedDocument = (new DocumentService())->getCompletedDocument($id);

            foreach ($document->participants as $participant) {
                event(new DocumentCompletedEvent($document, $findTheLastCompletedDocument, $participant));
            }
        }

        (new DocumentAuditTrailService($user, $document))->completeDocumentAuditTrail();

        return match ($document->entry_point) {
            'Docs' => $this->showMessage('This document is now complete'),
            'Notary', 'Affidavit', 'Video', 'CFO' => $this->showMessage('This session is now complete'),
        };
    }
}
