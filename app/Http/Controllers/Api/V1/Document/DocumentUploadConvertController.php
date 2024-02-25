<?php

namespace App\Http\Controllers\Api\V1\Document;

use App\Enums\EntryPoint;
use App\Http\Controllers\Controller;
use App\Http\Requests\Document\StoreDocumentUploadConvertFormRequest;
use App\Services\Document\DocumentConvService;
use App\Services\Document\DocumentService;
use App\Services\Subscription\SubscriptionRestrictionService;

class DocumentUploadConvertController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/v1/document-upload-convert",
     *      operationId="postDocumentUploadConvert",
     *      tags={"Documents"},
     *      summary="Post Documents Upload Convert",
     *      description="Post Documents Upload Convert",
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(ref="#/components/schemas/StoreDocumentUploadConvertFormRequest")
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
    public function store(StoreDocumentUploadConvertFormRequest $request)
    {
        // (new SubscriptionRestrictionService())->checkRestrictions('Number of Envelops', 'envelops');

        $documentProperty = [
            'user_id' => auth('api')->id(),
            'title' => $request['title'],
            'public' => true,
            'entry_point' => EntryPoint::Docs,
        ];

        $document = (new DocumentService())->createDocument($documentProperty);

        $request['files'] ? (new DocumentConvService())->collectAllRequest($request->only('files'), $document) : null;

        return $this->showOne((new DocumentService())->userDocumentById($document->id));
    }
}
