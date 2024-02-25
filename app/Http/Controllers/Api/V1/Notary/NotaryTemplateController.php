<?php

namespace App\Http\Controllers\Api\V1\Notary;

use App\Enums\EntryPoint;
use App\Http\Controllers\Controller;
use App\Http\Requests\Document\StoreDocumentFormRequest;
use App\Models\Document;
use App\Services\Document\DocumentConversionService;
use App\Services\Document\DocumentService;

class NotaryTemplateController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/notary/document-templates",
     *      operationId="NotaryDocumentTemplate",
     *      tags={"Notary"},
     *      summary="Create a Notary Template",
     *      description="Create a DocumentTemplate",
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
        $documents = Document::where('user_id', auth('api')->id())->where('is_a_template', true)->get();

        return $this->showAll($documents);
    }

    /**
     * @OA\Post(
     *      path="/api/v1/notary/document-templates",
     *      operationId="postNotaryDocumentTemplate",
     *      tags={"Notary"},
     *      summary="Post DocumentTemplate",
     *      description="Post DocumentTemplate",
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(ref="#/components/schemas/StoreDocumentTemplateFormRequest")
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
    public function store(StoreDocumentFormRequest $request)
    {
        $documentProperty = [
            'user_id' => auth('api')->id(),
            'title' => $request['title'],
            'public' => true,
            'is_a_template' => true,
            'entry_point' => EntryPoint::Docs,
        ];

        $document = (new DocumentService())->createDocument($documentProperty);

        $request['files'] ? (new DocumentConversionService())->collectAllRequest($request->only('files'), $document) : null;

        return $this->showOne((new DocumentService())->userDocumentById($document->id));
    }

    /**
     * @OA\Get(
     *      path="/api/v1/notary/document-templates/{id}",
     *      operationId="showNotaryDocumentTemplate",
     *      tags={"Notary"},
     *      summary="Show NotaryDocumentTemplate",
     *      description="Show NotaryDocumentTemplate",
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="NotaryDocumentTemplate ID",
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
        return (new DocumentService())->userDocumentById($id)
            ? $this->showOne((new DocumentService())->userDocumentById($id))
            : $this->errorResponse('Document does not exist', 409);
    }
}
