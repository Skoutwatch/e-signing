<?php

namespace App\Http\Controllers\Api\V1\Document;

use App\Enums\EntryPoint;
use App\Http\Controllers\Controller;
use App\Http\Requests\Document\StoreDocumentUploadFormRequest;
use App\Models\Document;
use App\Services\Document\DocumentConvService;
use App\Services\Document\DocumentService;

class DocumentUploadController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/v1/documents-uploads",
     *      operationId="postDocumentUpload",
     *      tags={"Documents"},
     *      summary="Post Document Upload",
     *      description="Post Document Upload",
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(ref="#/components/schemas/StoreDocumentUploadFormRequest")
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
    public function store(StoreDocumentUploadFormRequest $request)
    {
        $documentProperty = [
            'user_id' => auth('api')->id(),
            'public' => true,
            'entry_point' => EntryPoint::Docs,
        ];

        $document = (new DocumentService())->createDocument(array_merge($documentProperty, $request->except('files')));

        $request['parent_id'] ? (new DocumentConvService())->collectAllRequest($request->only('files'), $document) : null;

        return $this->showOne((new DocumentService())->userDocumentById($document->id));
    }
}
