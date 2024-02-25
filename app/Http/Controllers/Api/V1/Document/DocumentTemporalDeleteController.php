<?php

namespace App\Http\Controllers\Api\V1\Document;

use App\Http\Controllers\Controller;

class DocumentTemporalDeleteController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/documents-temporal-deleted",
     *      operationId="allDocumentsDeleted",
     *      tags={"Documents"},
     *      summary="show Document Deleted",
     *      description="show Document Deleted",
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
        return $this->showAll(auth('api')->user()->activeTeam->team->deletedDocuments);
    }
}
