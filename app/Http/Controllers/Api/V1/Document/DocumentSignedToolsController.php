<?php

namespace App\Http\Controllers\Api\V1\Document;

use App\Enums\DocumentParticipantStatus;
use App\Enums\DocumentStatus;
use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DocumentParticipant;

class DocumentSignedToolsController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/signed-documents",
     *      operationId="signedDocuments",
     *      tags={"Documents"},
     *      summary="signedDocuments",
     *      description="signedDocuments",
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
            ->whereIn('Status', [null, DocumentParticipantStatus::Signed, DocumentParticipantStatus::Approved, DocumentParticipantStatus::Sent])
            ->pluck('document_id')
            ->toArray();

        $userDocsAsOwner = DocumentParticipant::where('who_added_id', auth()->id())
            ->whereIn('Status', [null, DocumentParticipantStatus::Signed, DocumentParticipantStatus::Approved, DocumentParticipantStatus::Sent])
            ->pluck('document_id')
            ->toArray();

        $allDocs = array_merge($userDocsAsParticipants, $userDocsAsOwner);

        $documents = Document::whereIn('id', $allDocs)->where('status', DocumentStatus::Sent)->latest('updated_at')->get();

        return $this->showAll($documents);

    }
}
