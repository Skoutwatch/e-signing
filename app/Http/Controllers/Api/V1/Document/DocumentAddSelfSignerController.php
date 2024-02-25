<?php

namespace App\Http\Controllers\Api\V1\Document;

use App\Enums\DocumentParticipantRole;
use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Services\Document\DocumentParticipantService;
use App\Services\Document\DocumentService;

class DocumentAddSelfSignerController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/document-participant-add-self/{document_id}",
     *      operationId="AddSelfSignToDocument",
     *      tags={"DocumentParticipant"},
     *      summary="AddSelfSignToDocument",
     *      description="AddSelfSignToDocument",
     *
     *      @OA\Parameter(
     *          name="document_id",
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
        $document = Document::find($id);

        $lastOrderSequence = $document->participants()->orderBy('sequence_order', 'desc')->first();

        $lastOrderSequence = $lastOrderSequence ? (int) $lastOrderSequence->sequence_order + 1 : 1;

        $signerAlreadyExist = $document->participants->where('user_id', auth('api')->id())->first();

        if ($signerAlreadyExist) {
            return $signerAlreadyExist->delete()
                ? $this->showOne((new DocumentService())->userDocumentById($document->id))
                : $this->errorResponse('cannot remove yourself as signer', 409);
        } else {
            $attributes = [
                'role' => DocumentParticipantRole::Signer,
                'sequence_order' => $lastOrderSequence,
            ];

            (new DocumentParticipantService())->addParticipant($document, $attributes, auth('api')->user());
        }

        return $this->showOne((new DocumentService())->userDocumentByParentId($document->id));
    }
}
