<?php

namespace App\Http\Controllers\Api\V1\Unauthenticated;

use App\Enums\EntryPoint;
use App\Http\Controllers\Controller;
use App\Http\Requests\Document\StoreDocumentFormRequest;
use App\Http\Resources\Document\UnauthenticationDocumentResource;
use App\Http\Resources\Signlink\SignlinkDocumentResource;
use App\Models\Document;
use App\Services\Document\DocumentConvService;
use App\Services\Document\DocumentService;

class DocumentController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/v1/unauthenticated-documents",
     *      operationId="postUnauthenticatedDocument",
     *      tags={"Unauthenticated"},
     *      summary="Post Unauthenticated Documents",
     *      description="Post Unauthenticated Documents",
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(ref="#/components/schemas/StoreDocumentFormRequest")
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
    public function store(StoreDocumentFormRequest $request)
    {
        $documentProperty = [
            'title' => $request['title'],
            'public' => true,
            'entry_point' => EntryPoint::Docs,
        ];

        $document = (new DocumentService())->createDocument($documentProperty);

        $request['files'] ? (new DocumentConvService())->collectAllRequest($request->only('files'), $document) : null;

        return new UnauthenticationDocumentResource($document);
    }

    /**
     * @OA\Get(
     *      path="/api/v1/unauthenticated-documents/{id}",
     *      operationId="showUnauthenticatedDocuments",
     *      tags={"Unauthenticated"},
     *      summary="Show Unauthenticated Documents",
     *      description="Show Unauthenticated Documents",
     *
     *      @OA\Parameter(
     *          name="id",
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
     * )
     */
    public function show($id)
    {
        return new SignlinkDocumentResource(Document::with('uploads', 'signlinkTools')->find($id));
    }
}
