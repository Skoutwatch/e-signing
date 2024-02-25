<?php

namespace App\Http\Controllers\Api\V1\Document;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DocumentLocker;
use App\Models\DocumentParticipant;

class DocumentParticipantReceivedController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/documents-received",
     *      operationId="allDocumentsReceived",
     *      tags={"Documents"},
     *      summary="show Document Received",
     *      description="show Document Received",
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
        $received = DocumentParticipant::where('user_id', auth('api')->id())->where('who_added_id', '!=', auth('api')->id())->pluck('document_id')->toArray();

        $locker = DocumentLocker::where('user_id', auth('api')->id())->get()->pluck('document_id')->toArray();

        $arrayDifference = array_diff($received, $locker);

        $documents = Document::whereIn('id', $arrayDifference)->where('status', 'Sent')->latest()->get();

        return $this->showAll($documents);
    }
}
