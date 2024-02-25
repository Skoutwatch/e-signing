<?php

namespace App\Http\Controllers\Api\V1\Document;

use App\Enums\DocumentParticipantStatus;
use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DocumentParticipant;
use App\Services\Document\DocumentService;

class DeclinedDocumentController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/declined-documents",
     *      operationId="allDeclinedDocument",
     *      tags={"DocumentParticipant"},
     *      summary="AllDeclinedDocuments",
     *      description="AllDeclinedDocuments",
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
    public function index()
    {
        $userDeclinedDocsAsParticipants = DocumentParticipant::where('status', DocumentParticipantStatus::Declined)
            ->where('user_id', auth()->id())
            ->pluck('document_id')
            ->toArray();

        $userDeclinedDocsAsOwner = DocumentParticipant::where('status', DocumentParticipantStatus::Declined)
            ->where('who_added_id', auth()->id())
            ->pluck('document_id')
            ->toArray();

        $allDeclinedDocs = array_merge($userDeclinedDocsAsParticipants, $userDeclinedDocsAsOwner);

        $documents = Document::whereIn('id', $allDeclinedDocs)
            ->orderBy('created_at', 'desc')
            ->get();

        return $this->showAll($documents);
    }

    /**
     * @OA\Get(
     *      path="/api/v1/declined-documents/{id}",
     *      operationId="DeclinedDocument",
     *      tags={"DocumentParticipant"},
     *      summary="UserDeclinedDocument",
     *      description="UserDeclinedDocument",
     *
     *      @OA\Parameter(
     *          name="document_id",
     *          description="Documents ID",
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
        (new DocumentService())->processDocument($id);

        return (new DocumentService())->userDeclinedDocument($id)
            ? $this->showOne((new DocumentService())->userDeclinedDocument($id))
            : $this->errorResponse('Document does not exist', 409);
    }
}
