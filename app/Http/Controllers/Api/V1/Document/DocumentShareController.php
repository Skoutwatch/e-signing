<?php

namespace App\Http\Controllers\Api\V1\Document;

use App\Enums\DocumentUploadStatus;
use App\Events\Document\DocumentShareEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Document\StoreDocumentShareFormRequest;
use App\Models\Document;
use App\Models\DocumentUpload;

class DocumentShareController extends Controller
{
    /**
     * @OA\Put(
     *      path="/api/v1/document-share/{id}",
     *      operationId="postDocumentShare",
     *      tags={"Documents"},
     *      summary="Post DocumentsShare",
     *      description="Post DocumentsShare",
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
     *          @OA\JsonContent(ref="#/components/schemas/StoreDocumentShareFormRequest")
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
    public function update(StoreDocumentShareFormRequest $request, $id)
    {
        $document = Document::find($id);

        $findTheLastCompletedDocument = DocumentUpload::where('document_id', $document->id)
            ->where('status', DocumentUploadStatus::Completed)
            ->orWhere('status', DocumentUploadStatus::Locked)
            ->first();

        if ($findTheLastCompletedDocument == null) {
            return $this->errorResponse('The completed part of this document was not verfied. Please contact support', 409);
        }

        foreach ($request['documents'] as $docs) {
            event(new DocumentShareEvent($document, $findTheLastCompletedDocument, $docs['email']));
        }

        return $this->showMessage('Email sent');
    }
}
