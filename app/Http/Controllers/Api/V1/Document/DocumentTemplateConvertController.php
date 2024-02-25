<?php

namespace App\Http\Controllers\Api\V1\Document;

use App\Http\Controllers\Controller;
use App\Models\DocumentTemplate;
use App\Services\Document\DocumentConversionService;
use App\Services\Document\DocumentService;

class DocumentTemplateConvertController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/document-template-convert/{id}",
     *      operationId="showDocumentTemplateConvert",
     *      tags={"DocumentTemplate"},
     *      summary="Show DocumentTemplateConvert",
     *      description="Show DocumentTemplateConvert",
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
        $template = DocumentTemplate::find($id);

        $documentProperty = [
            'user_id' => auth('api')->id(),
            'title' => $template->title,
            'public' => true,
        ];

        $document = (new DocumentService())->createDocument($documentProperty);

        (new DocumentConversionService())->storeTemplatePdf($template->file, $document, null);

        return $this->showOne((new DocumentService())->userDocumentById($document->id));
    }
}
