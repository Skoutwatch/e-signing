<?php

namespace App\Http\Controllers\Api\V1\Document;

use App\Http\Controllers\Controller;
use App\Models\DocumentResourceTool;

class DocumentUnsignedToolsController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/unsigned-documents",
     *      operationId="unsignedDocuments",
     *      tags={"Documents"},
     *      summary="unsignedDocuments",
     *      description="unsignedDocuments",
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
        $unsignedNotes = DocumentResourceTool::where('user_id', auth('api')->id())->where('signed', false)->groupBy('document_id')->get();

        return $this->showAll($unsignedNotes);
    }
}
