<?php

namespace App\Http\Controllers\Api\V1\Document;

use App\Http\Controllers\Controller;
use App\Http\Requests\Document\StoreDocumentTemplateFormRequest;
use App\Models\DocumentTemplate;
use App\Traits\Image\ImageHelper;

class DocumentTemplateController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/document-templates",
     *      operationId="DocumentTemplate",
     *      tags={"DocumentTemplate"},
     *      summary="Create a DocumentTemplate",
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
        return $this->showAll(DocumentTemplate::all());
    }

    /**
     * @OA\Post(
     *      path="/api/v1/document-templates",
     *      operationId="postDocumentTemplate",
     *      tags={"DocumentTemplate"},
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
    public function store(StoreDocumentTemplateFormRequest $request)
    {
    }

    /**
     * @OA\Get(
     *      path="/api/v1/document-templates/{id}",
     *      operationId="showDocumentTemplate",
     *      tags={"DocumentTemplate"},
     *      summary="Show DocumentTemplate",
     *      description="Show DocumentTemplate",
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="DocumentTemplate ID",
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
    }

    /**
     * @OA\Delete(
     *      path="/api/v1/document-templates/{id}",
     *      operationId="deleteDocumentTemplate",
     *      tags={"DocumentTemplate"},
     *      summary="Delete DocumentTemplate",
     *      description="Delete DocumentTemplate",
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="DocumentTemplate ID",
     *          required=true,
     *          in="path",
     *
     *          @OA\Schema(
     *              type="integer"
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
    public function destroy(DocumentTemplate $documentTemplate)
    {
        ImageHelper::deleteAnything($documentTemplate->file) ? null : $this->errorResponse('cannot delete entry', 404);

        return $documentTemplate->delete()
                ? $this->showMessage('exercise entry deleted')
                : $this->errorResponse('cannot delete entry', 404);
    }
}
