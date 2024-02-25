<?php

namespace App\Http\Controllers\Api\V1\Document;

use App\Events\Realtime\AllDocumentToolsEvent;
use App\Http\Controllers\Controller;
use App\Http\Resources\Document\DocumentResourceToolResource;
use App\Models\Document;
use App\Models\DocumentResourceTool;

class DocumentResourceToolUserController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/user-document-resource-tool/{document_id}",
     *      operationId="getUserDocumentResourceTool",
     *      tags={"DocumentResourceTool"},
     *      summary="getUserDocumentResourceTool",
     *      description="getUserDocumentResourceTool",
     *
     *      @OA\Parameter(
     *          name="document_id",
     *          description="Documents ID",
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
        $document = Document::find($id);

        $toolsViaDocuments = $document->documentUploads->merge($document->allDocumentUploads)->pluck('id')->toArray();

        $tools = DocumentResourceTool::with('appendPrint')->whereIn('document_upload_id', $toolsViaDocuments)->orderBy('created_at', 'DESC')->get();

        broadcast(new AllDocumentToolsEvent($document));

        return DocumentResourceToolResource::collection($tools);
    }
}
