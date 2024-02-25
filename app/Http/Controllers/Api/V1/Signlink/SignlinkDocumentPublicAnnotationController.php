<?php

namespace App\Http\Controllers\Api\V1\Signlink;

use App\Http\Controllers\Controller;
use App\Http\Requests\Signlink\UpdatePublicAnnotationSignlinkDocumentsFormRequest;
use App\Models\DocumentResourceTool;
use App\Services\Document\DocumentConversionService;

class SignlinkDocumentPublicAnnotationController extends Controller
{
    /**
     * @OA\Put(
     *      path="/api/v1/signlink-public-annotation/{id}",
     *      operationId="updatePublicSignlinkDocuments",
     *      tags={"SignlinkDocuments"},
     *      summary="Update SignlinkDocuments",
     *      description="Update SignlinkDocuments",
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="SignlinkDocuments ID",
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
     *          @OA\JsonContent(ref="#/components/schemas/UpdatePublicAnnotationSignlinkDocumentsFormRequest")
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
    public function update(UpdatePublicAnnotationSignlinkDocumentsFormRequest $request, $id)
    {
        $tool = DocumentResourceTool::where('id', $id)
            ->where('document_upload_id', $request['document_upload_id'])
            ->where('document_id', $request['document_id'])
            ->first();

        if (! $tool) {
            return $this->errorResponse('Invalid sign mode', 409);
        }

        $value = $request['value'] ? (new DocumentConversionService())->fileStorage($request['value'], $tool) : null;

        $storeValue = $value != null ? (new DocumentConversionService())->storeImage($value['storage']) : null;

        return $tool->update(
            array_merge(
                $request->validated(),
                [
                    'value' => $storeValue ? $storeValue : $request['value'],
                    'document_id' => $tool ? $tool->document_id : null,
                ]
            )
        )
            ? $this->showMessage('Tool annotated')
            : $this->errorResponse('An error occurred while annotating', 409);
    }
}
