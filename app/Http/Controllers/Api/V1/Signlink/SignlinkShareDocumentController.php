<?php

namespace App\Http\Controllers\Api\V1\Signlink;

use App\Events\Signlink\SignlinkShareLinkEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Document\StoreDocumentShareFormRequest;
use App\Models\Document;

class SignlinkShareDocumentController extends Controller
{
    /**
     * @OA\Put(
     *      path="/api/v1/signlink-share-link/{id}",
     *      operationId="postSignlinkShare",
     *      tags={"SignlinkDocuments"},
     *      summary="Post SignlinkShare",
     *      description="Post SignlinkShare",
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

        foreach ($request['documents'] as $docs) {
            event(new SignlinkShareLinkEvent($document, $docs['email']));
        }

        return $this->showMessage('Email sent');
    }
}
