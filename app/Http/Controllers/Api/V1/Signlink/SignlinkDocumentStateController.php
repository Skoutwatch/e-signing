<?php

namespace App\Http\Controllers\Api\V1\Signlink;

use App\Http\Controllers\Controller;
use App\Http\Resources\Signlink\SignlinkDocumentResource;
use App\Models\Document;

class SignlinkDocumentStateController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/signlink-public-state/{id}",
     *      operationId="showSignlinkDocumentState",
     *      tags={"SignlinkDocuments"},
     *      summary="Show SignlinkDocumentState",
     *      description="Show SignlinkDocumentState",
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="SignlinkDocumentState ID",
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
        return new SignlinkDocumentResource(Document::with('uploads', 'signlinkTools')->find($id));
    }
}
