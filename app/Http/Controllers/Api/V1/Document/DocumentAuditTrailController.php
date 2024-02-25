<?php

namespace App\Http\Controllers\Api\V1\Document;

use App\Http\Controllers\Controller;
use App\Http\Requests\Audit\StoreAuditTrailActionFormRequest;
use App\Models\AuditTrail;
use App\Models\Document;
use App\Models\User;
use App\Services\Document\DocumentAuditTrailService;

class DocumentAuditTrailController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/document-audit-trail/{id}",
     *      operationId="showDocumentAuditTrail",
     *      tags={"Documents"},
     *      summary="Show DocumentAuditTrail",
     *      description="Show DocumentAuditTrail",
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
        $trail = AuditTrail::where('subject_id', $id)->where('subject_type', 'Document')->orderBy('created_at', 'ASC')->get();

        return $this->showAll($trail);
    }

    /**
     * @OA\Put(
     *      path="/api/v1/document-audit-trail/{id}",
     *      operationId="updateAuditTrail",
     *      tags={"Documents"},
     *      summary="Replace AuditTrail",
     *      description="Replace AuditTrail",
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
     *     ),
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(ref="#/components/schemas/StoreAuditTrailActionFormRequest")
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
    public function update(StoreAuditTrailActionFormRequest $request, $id)
    {
        $document = Document::find($id);
        $user = User::find($request['user_id']);

        match ($request['action']) {
            'JoinSession' => (new DocumentAuditTrailService($user, $document))->joinSessionAuditTrail(),
            'LeftSession' => (new DocumentAuditTrailService($user, $document))->leftSessionAuditTrail(),
            'StartSession' => (new DocumentAuditTrailService($user, $document))->startSessionAuditTrail(),
            'EndSession' => (new DocumentAuditTrailService($user, $document))->endSessionAuditTrail(),
            'StartRecording' => (new DocumentAuditTrailService($user, $document))->startRecordingAuditTrail(),
            'EndRecording' => (new DocumentAuditTrailService($user, $document))->endRecordingAuditTrail(),
        };

        return $this->showMessage('This document session has been updated');
    }
}
