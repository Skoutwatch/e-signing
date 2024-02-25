<?php

namespace App\Http\Controllers\Api\V1\Document;

use App\Http\Controllers\Controller;
use App\Http\Requests\Document\StoreDocumentDeleteFormRequest;
use App\Models\Document;

class DocumentDeleteMultipleController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/v1/document-multiple-delete",
     *      operationId="postDocumentDelete",
     *      tags={"Documents"},
     *      summary="Post DocumentsDelete",
     *      description="Post DocumentsDelete",
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(ref="#/components/schemas/StoreDocumentDeleteFormRequest")
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
    public function store(StoreDocumentDeleteFormRequest $request)
    {
        foreach ($request['documents'] as $doc) {
            $document = Document::withTrashed()->find($doc['document_id']);

            if (! empty($document->tools)) {
                foreach ($document->tools as $tools) {
                    $doc['permanent_delete'] == true ? $tools->forceDelete() : null;
                }
            }

            if (! empty($document->childrenDocuments)) {

                foreach ($document->childrenDocuments as $document) {
                    foreach ($document->uploads as $upload) {
                        // $upload->file_url && $doc['permanent_delete'] == true ? Storage::disk('s3')->delete($upload->file_url) : null;
                        $doc['permanent_delete'] == true ? $upload->forceDelete() : null;
                    }

                    $doc['permanent_delete'] == true ? $document->forceDelete() : $document->delete();
                }
            }

            if (! empty($document->uploads)) {
                foreach ($document->uploads as $upload) {
                    // $upload->file_url && $doc['permanent_delete'] == true ? Storage::disk('s3')->delete($upload->file_url) : null;
                    $doc['permanent_delete'] == true ? $upload->forceDelete() : null;
                }
            }

            if (! empty($document->participants)) {
                foreach ($document->participants as $participant) {
                    $doc['permanent_delete'] == true ? $participant->forceDelete() : null;
                }
            }

            if (! empty($document->signlinkUploads)) {
                foreach ($document->signlinkUploads as $signlinkUpload) {
                    $doc['permanent_delete'] == true ? $signlinkUpload->forceDelete() : null;
                }
            }

            if (! empty($document->userFormData)) {
                foreach ($document->userFormData as $form) {
                    $doc['permanent_delete'] == true ? $form->forceDelete() : null;
                }
            }

            if (! empty($document->signlinkTools)) {
                foreach ($document->signlinkTools as $tool) {
                    $doc['permanent_delete'] == true ? $tool->forceDelete() : null;
                }
            }

            $locker = auth('api')->user()->locker->where('document_id', $doc['document_id'])->first();

            $locker ? $locker->delete() : null;

            $doc['permanent_delete'] === true ? $document->forceDelete() : $document->delete();
        }

        return $this->showMessage('Document(s) deleted');
    }
}
