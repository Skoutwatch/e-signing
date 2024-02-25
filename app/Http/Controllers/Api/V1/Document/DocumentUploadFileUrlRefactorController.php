<?php

namespace App\Http\Controllers\Api\V1\Document;

use App\Http\Controllers\Controller;
use App\Models\DocumentUpload;

class DocumentUploadFileUrlRefactorController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/document-refactor-urls",
     *      operationId="DocumentRefactorURls",
     *      tags={"Documents"},
     *      summary="Create a DocumentRefactorURls",
     *      description="Create a DocumentRefactorURls",
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
        return DocumentUpload::all();
    }
}
