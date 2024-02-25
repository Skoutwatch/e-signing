<?php

namespace App\Http\Controllers\Api\V1\DocumentExport;

use App\Http\Controllers\Controller;
use App\Services\Document\DocumentService;

class DocumentExportController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/document-export/{id}",
     *      operationId="showDocumentExport",
     *      tags={"Document"},
     *      summary="Show DocumentExport",
     *      description="Show DocumentExport",
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="Document Export ID",
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
     * )
     */
    public function show($id)
    {
        return (new DocumentService())->processDocument($id);
    }
}
