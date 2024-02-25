<?php

namespace App\Http\Controllers\Api\V1\Signlink;

use App\Http\Controllers\Controller;
use App\Http\Resources\Signlink\SignlinkDocumentResponseResource;
use App\Models\Document;
use App\Models\SignlinkDocumentUserFormData;

class SignlinkDocumentResponseController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/signlink-responses/{id}",
     *      operationId="showSignlinkDocumentsResponses",
     *      tags={"SignlinkDocuments"},
     *      summary="Show SignlinkDocumentsResponses",
     *      description="Show SignlinkDocumentsResponses",
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="SignlinkDocuments ID",
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
        $document = Document::find($id);

        $responses = $document->signlinkResponses->pluck('id')->toArray();

        $forms = SignlinkDocumentUserFormData::with('document', 'document.uploads')->whereIn('document_id', $responses)->get();

        return SignlinkDocumentResponseResource::collection($forms);
    }
}
