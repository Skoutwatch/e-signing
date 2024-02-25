<?php

namespace App\Http\Controllers\Api\V1\Document;

use App\Enums\DocumentStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Document\StoreDocumentResourceFormRequest;
use App\Http\Requests\Document\UpdateDocumentResourceFormRequest;
use App\Http\Resources\Document\DocumentResourceToolResource;
use App\Models\AppendPrint;
use App\Models\Document;
use App\Models\DocumentResourceTool;
use App\Models\DocumentUpload;
use App\Models\User;
use App\Services\Document\DocumentAuditTrailService;
use App\Services\Document\DocumentParticipantService;
use App\Services\Document\DocumentResourceToolService;
use App\Services\ProcessDocument\HtmlToPdfService;
use App\Traits\Image\AwsS3;

class DocumentResourceToolController extends Controller
{
    use AwsS3;

    /**
     * @OA\Post(
     *      path="/api/v1/document-resource-tools",
     *      operationId="postDocumentResourceTool",
     *      tags={"DocumentResourceTool"},
     *      summary="Post DocumentResourceTool",
     *      description="Post DocumentResourceTool",
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(ref="#/components/schemas/StoreDocumentResourceFormRequest")
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
    public function store(StoreDocumentResourceFormRequest $request)
    {
        $documentUpload = DocumentUpload::find($request['document_upload_id']);

        $document = Document::findOrFail($request['document_id']);

        $user = User::find(auth('api')->id());

        $incomingValue = (($request['value']) != null ? true : false);

        $tool = $user->userWhoCreatedATools()->create(
            array_merge($request->validated(), [
                'signed' => $incomingValue,
            ]
            ));

        $documentUpload->document->touch();

        (new HtmlToPdfService())->html($documentUpload, $documentUpload->document);

        (new DocumentAuditTrailService($user, $document))->annotateToolAuditTrail($tool);

        $documentUpload->document->participants->where('id', $user->id)?->first()?->update([
            'notification_count' => 0,
        ]);

        ($document->status === DocumentStatus::Sent && $document->has_sequence_order === 0) ? (new DocumentParticipantService())->documentParticipantResetMail($document) : null;

        return DocumentResourceToolResource::collection(DocumentResourceTool::with('appendPrint')->where('document_upload_id', $tool?->upload?->id)->get());
    }

    /**
     * @OA\Get(
     *      path="/api/v1/document-resource-tools/{id}",
     *      operationId="showDocumentResourceTool",
     *      tags={"DocumentResourceTool"},
     *      summary="Show DocumentResourceTool",
     *      description="Show DocumentResourceTool",
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
        return new DocumentResourceToolResource(DocumentResourceTool::with('appendPrint')->findOrFail($id));
    }

    /**
     * @OA\Put(
     *      path="/api/v1/document-resource-tools/{id}",
     *      operationId="updateDocumentResourceTool",
     *      tags={"DocumentResourceTool"},
     *      summary="Update DocumentResourceTool",
     *      description="Update DocumentResourceTool",
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="DocumentResourceTool ID",
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
     *          @OA\JsonContent(ref="#/components/schemas/UpdateDocumentResourceFormRequest")
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
    public function update(UpdateDocumentResourceFormRequest $request, $id)
    {
        $tool = DocumentResourceTool::findOrFail($id);

        $document = Document::findOrFail($request['document_id']);

        $incomingPrintsId = $request['append_print_id'] ? AppendPrint::find($request['append_print_id']) : null;

        $incomingValue = $incomingPrintsId ? $incomingPrintsId->file : (isset($request['value']) ? $request['value'] : null);

        $finalValue = $incomingValue ? $incomingValue : $tool->append_print_id;

        $documentUpload = DocumentUpload::find($request['document_upload_id']);

        $toolOwner = $request['user_id'] ? User::find($request['user_id']) : null;

        $tool->update(
            array_merge(
                $request->except('value'),
                [
                    'value' => $finalValue,
                    'signed' => $finalValue ? true : false,
                ]
            )
        );

        isset($finalValue) ? (new DocumentAuditTrailService($toolOwner, $document))->signedToolAuditTrail($tool) : null;

        $documentUpload->document->touch();

        (new HtmlToPdfService())->html($documentUpload, $documentUpload->document);

        (new DocumentResourceToolService())->checkIfSignatureIsCompleted($documentUpload->document);

        return new DocumentResourceToolResource(DocumentResourceTool::with('appendPrint')->findorFail($id));
    }

    /**
     * @OA\Delete(
     *      path="/api/v1/document-resource-tools/{id}",
     *      operationId="deleteDocumentResourceTool",
     *      tags={"DocumentResourceTool"},
     *      summary="Delete DocumentResourceTool",
     *      description="Delete DocumentResourceTool",
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="DocumentResourceTool ID",
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

        (new DocumentAuditTrailService(auth('api')->user(), $tool?->upload?->document?->parentDocument))->removeToolAuditTrail($tool);

        return $tool->delete()
                ? $this->showMessage('Print deleted')
                : $this->errorResponse('cannot delete document resource', 404);
    }
}
