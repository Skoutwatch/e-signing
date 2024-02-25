<?php

namespace App\Http\Controllers\Api\V1\Document;

use App\Http\Controllers\Controller;
use App\Http\Requests\Document\StoreDocumentRestoreFormRequest;
use App\Models\Document;

class DocumentRestoreMultipleController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/v1/document-multiple-restore",
     *      operationId="postDocumentRestore",
     *      tags={"Documents"},
     *      summary="Post DocumentsRestore",
     *      description="Post DocumentsRestore",
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(ref="#/components/schemas/StoreDocumentRestoreFormRequest")
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
    public function store(StoreDocumentRestoreFormRequest $request)
    {
        foreach ($request['documents'] as $doc) {
            $document = Document::withTrashed()->find($doc['document_id'])->update(['deleted_at' => null]);
        }

        return $this->showMessage('Your document(s) have been restored');
    }
}
