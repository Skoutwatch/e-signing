<?php

namespace App\Http\Controllers\Api\V1\Document;

use App\Http\Controllers\Controller;
use App\Models\Document;

class DocumentImagesController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/document-image-tools/{id}",
     *      operationId="showDocumentImages",
     *      tags={"Documents"},
     *      summary="Show DocumentImages",
     *      description="Show DocumentImages",
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
        return $this->showAll(Document::with('tools.appendPrint')->find($id)->toolImages);
    }
}
