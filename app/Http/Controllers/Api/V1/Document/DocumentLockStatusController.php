<?php

namespace App\Http\Controllers\Api\V1\Document;

use App\Enums\DocumentStatus;
use App\Http\Controllers\Controller;
use App\Models\Document;

class DocumentLockStatusController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/document-lock-status/{id}",
     *      operationId="updateDocumentLockStatus",
     *      tags={"Documents"},
     *      summary="Show updateDocumentLockStatus",
     *      description="Show updateDocumentLockStatus",
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

        if ($document->status == DocumentStatus::Locked) {
            return $this->errorResponse('This document is already locked', 409);
        }

        if ($document->status != DocumentStatus::Completed) {
            return $this->errorResponse('This document needs to be completed before it can be locked', 409);
        }

        $document->update(['status' => DocumentStatus::Locked]);

        auth('api')->user()->locker()->create([
            'document_id' => $document->id,
        ]);

        return $this->showMessage('This document has been locked');
    }
}
