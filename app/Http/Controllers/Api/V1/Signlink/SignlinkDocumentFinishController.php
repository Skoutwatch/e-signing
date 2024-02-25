<?php

namespace App\Http\Controllers\Api\V1\Signlink;

use App\Events\Signlink\SignlinkCompletedEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Signlink\UpdateSignlinkUserFormDataFormRequest;
use App\Models\Document;
use App\Models\SignlinkDocumentUserFormData;
use App\Services\Document\DocumentConversionService;
use App\Services\Document\DocumentConvService;

class SignlinkDocumentFinishController extends Controller
{
    /**
     * @OA\Put(
     *      path="/api/v1/signlink-complete/{id}",
     *      operationId="completeSignlinkDocument",
     *      tags={"SignlinkDocuments"},
     *      summary="CompleteSignlinkDocument",
     *      description="CompleteSignlinkDocument",
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="Document Signlink ID",
     *          required=true,
     *          in="path",
     *
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(ref="#/components/schemas/UpdateSignlinkUserFormDataFormRequest")
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
    public function update(UpdateSignlinkUserFormDataFormRequest $request, $id)
    {
        $document = Document::findOrFail($id);

        if (! $document->parent_id) {
            return $this->errorResponse('The document is has no parent id', 409);
        }

        if ($document->status == 'Completed') {
            return $this->errorResponse('The document has already been signed', 409);
        }

        $request['files'] ? (new DocumentConvService())->collectAllRequest($request->only('files'), $document, 'Processed') : null;

        $storeValue = $request['files'] ? (new DocumentConversionService())->collectAllRequest($request->only('files'), $document, 'Completed') : null;

        $signlinkForm = SignlinkDocumentUserFormData::create([
            'first_name' => $request['first_name'],
            'last_name' => $request['last_name'],
            'email' => $request['email'],
            'phone' => $request['phone'],
            'document_id' => $document->id,
            'file_url' => $storeValue ? $storeValue->file_url : null,
        ]);

        $document->update(['status' => 'Completed']);

        event(new SignlinkCompletedEvent($signlinkForm, $document, $document->parentDocument));

        return $this->showMessage('This document is now completed');
    }

    public function fileStorage($file, $print)
    {
        return (new DocumentConversionService())->fileStorage($file, $print);
    }

    public function uploadPrint($value)
    {
        return (new DocumentConversionService())->storeImage($value);
    }
}
