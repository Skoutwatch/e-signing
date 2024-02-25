<?php

namespace App\Http\Controllers\Api\V1\Signlink;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DocumentResourceTool;
use ErrorException;

class SignlinkDocumentResourceToolUser extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/signlink-annotation-tools/{document_id}",
     *      operationId="getSignlinkDocumentResourceTool",
     *      tags={"SignlinkDocuments"},
     *      summary="getSignlinkrDocumentResourceTool",
     *      description="getSignlinkDocumentResourceTool",
     *
     *      @OA\Parameter(
     *          name="document_id",
     *          description="signlink ID",
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
        $toolsViaDocuments = Document::find($id)?->uploads
                    ? Document::find($id)?->uploads->pluck('id')->toArray()
                    : throw new ErrorException('Document tools not found', 409);
        $tools = DocumentResourceTool::with('appendPrint')->whereIn('document_upload_id', $toolsViaDocuments)->orderBy('created_at', 'DESC')->get();

        return $this->showAll($tools);
    }
}
