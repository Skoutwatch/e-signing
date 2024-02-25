<?php

namespace App\Http\Controllers\Api\V1\Signlink;

use App\Http\Controllers\Controller;
use App\Http\Requests\Signlink\StoreSignlinkAnnotationFormRequest;
use App\Http\Requests\Signlink\UpdateSignlinkAnnotationFormRequest;
use App\Models\DocumentResourceTool;
use App\Models\DocumentUpload;
use App\Services\Document\DocumentConversionService;

class SignlinkDocumentAnnotationController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/v1/signlink-annotations",
     *      operationId="postSignlinkAnnotation",
     *      tags={"SignlinkDocuments"},
     *      summary="Post SignlinkAnnotation",
     *      description="Post SignlinkAnnotation",
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(ref="#/components/schemas/StoreSignlinkAnnotationFormRequest")
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
    public function store(StoreSignlinkAnnotationFormRequest $request)
    {
        $documentUpload = DocumentUpload::find($request['document_upload_id']);

        $tool = DocumentResourceTool::create([
            'document_upload_id' => $request['document_upload_id'],
            'document_id' => $documentUpload ? $documentUpload->document_id : null,
            'who_added_id' => auth('api')->id(),
            'tool_name' => $request['tool_name'],
            'tool_class' => $request['tool_class'],
            'tool_height' => $request['tool_height'],
            'tool_width' => $request['tool_width'],
            'tool_pos_top' => $request['tool_pos_top'],
            'tool_pos_left' => $request['tool_pos_left'],
            'allow_signature' => $request['allow_signature'],
            'user_id' => auth('api')->id(),
        ]);

        return $this->showAll(DocumentResourceTool::where('document_upload_id', $tool?->upload?->id)->get());
    }

    /**
     * @OA\Get(
     *      path="/api/v1/signlink-annotations/{id}",
     *      operationId="showSignlinkAnnotation",
     *      tags={"SignlinkDocuments"},
     *      summary="Show SignlinkAnnotation",
     *      description="Show SignlinkAnnotation",
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
     *      security={ {"bearerAuth": {}} },
     * )
     */
    public function show($id)
    {
        return $this->showOne(DocumentResourceTool::findOrFail($id));
    }

    /**
     * @OA\Put(
     *      path="/api/v1/signlink-annotations/{id}",
     *      operationId="updateSignlinkAnnotation",
     *      tags={"SignlinkDocuments"},
     *      summary="Update SignlinkAnnotation",
     *      description="Update SignlinkAnnotation",
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="SignlinkAnnotation ID",
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
     *          @OA\JsonContent(ref="#/components/schemas/UpdateSignlinkAnnotationFormRequest")
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
    public function update(UpdateSignlinkAnnotationFormRequest $request, $id)
    {
        $tool = DocumentResourceTool::findOrFail($id);

        $documentUpload = DocumentUpload::find($request['document_upload_id']);

        $value = $request['value'] ? (new DocumentConversionService())->fileStorage($request['value'], $tool) : null;

        $storeValue = $value != null ? (new DocumentConversionService())->storeImage($value['storage']) : null;

        $tool->update(
            array_merge(
                $request->validated(),
                [
                    'value' => $storeValue ? $storeValue : $request['value'],
                    'document_id' => $documentUpload ? $documentUpload->document_id : null,
                ]
            )
        );

        return $this->showOne(DocumentResourceTool::findorFail($id));
    }

    /**
     * @OA\Delete(
     *      path="/api/v1/signlink-annotations/{id}",
     *      operationId="deleteSignlinkAnnotation",
     *      tags={"SignlinkDocuments"},
     *      summary="Delete SignlinkAnnotation",
     *      description="Delete SignlinkAnnotation",
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="SignlinkAnnotation ID",
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
    public function destroy($id)
    {
        $tool = DocumentResourceTool::findOrFail($id);

        return $tool->delete()
                ? $this->showMessage('Print deleted')
                : $this->errorResponse('cannot delete document resource', 404);
    }
}
