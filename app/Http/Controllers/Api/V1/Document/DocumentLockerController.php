<?php

namespace App\Http\Controllers\Api\V1\Document;

use App\Enums\DocumentStatus;
use App\Enums\DocumentUploadStatus;
use App\Enums\EntryPoint;
use App\Enums\ScheduleSessionStatus;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Document\StoreDocumentLockerFormRequest;
use App\Models\Document;
use App\Models\DocumentLocker;
use App\Models\ScheduleSession;
use App\Services\Document\DocumentConversionService;
use App\Services\Document\DocumentConvService;
use App\Services\Document\DocumentService;

class DocumentLockerController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/document-locker",
     *      operationId="documentLocker",
     *      tags={"Documents"},
     *      summary="Get document locker",
     *      description="get document locker",
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
        $type = match (auth('api')->user()->role) {
            UserRole::Notary => 'notary_id',
            UserRole::User => 'customer_id',
            default => 'customer_id',
        };

        $session = ScheduleSession::where($type, auth('api')->id())
            ->where('status', ScheduleSessionStatus::Completed)
            ->get()
            ->pluck('schedule_id')
            ->toArray();

        $lockerIds = DocumentLocker::where('user_id', auth('api')->id())->get()->pluck('document_id')->toArray();

        $documentIds = array_merge($session, $lockerIds);

        $documents = Document::whereIn('id', $documentIds)->get();

        return $this->showAll($documents);
    }

    /**
     * @OA\Post(
     *      path="/api/v1/document-locker",
     *      operationId="postDocumentLocker",
     *      tags={"Documents"},
     *      summary="Post DocumentLocker",
     *      description="Post DocumentLocker",
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(ref="#/components/schemas/StoreDocumentLockerFormRequest")
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
    public function store(StoreDocumentLockerFormRequest $request)
    {
        $documentProperty = [
            'user_id' => auth('api')->id(),
            'title' => $request['title'],
            'public' => true,
            'status' => DocumentStatus::Locked,
            'entry_point' => EntryPoint::Docs,
        ];

        $document = (new DocumentService())->createDocument($documentProperty);

        $request['files'] ? (new DocumentConversionService())->collectAllRequest($request->only('files'), $document, DocumentUploadStatus::Completed) : null;

        (new DocumentConvService())->collectAllRequest($request->only('files'), $document, DocumentUploadStatus::Processed);

        $alreadyLocked = auth('api')->user()->locker->where('document_id', $document->id)->first();

        $alreadyLocked == null ? auth('api')->user()->locker()->create([
            'document_id' => $document->id,
        ]) : null;

        return $this->showMessage('Document added to locker');
    }

    /**
     * @OA\Get(
     *      path="/api/v1/document-locker/{id}",
     *      operationId="showDocumentLocker",
     *      tags={"Documents"},
     *      summary="Show DocumentLocker",
     *      description="Show DocumentLocker",
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="Document ID",
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

        $alreadyLocked = auth('api')->user()->locker->where('document_id', $document->id)->first();

        $alreadyLocked === null ? auth('api')->user()->locker()->create([
            'document_id' => $document->id,
        ]) : null;

        return $this->showMessage('Document has been added successfully');
    }

    /**
     * @OA\Delete(
     *      path="/api/v1/document-locker/{id}",
     *      operationId="deleteDocumentLocker",
     *      tags={"Documents"},
     *      summary="Delete DocumentLocker",
     *      description="Delete DocumentLocker",
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="DocumentLocker ID",
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
        $alreadyLocked = auth('api')->user()->locker->where('document_id', $id)->first();

        $alreadyLocked ? $alreadyLocked->delete() : null;

        return $this->showMessage('Document deleted');
    }
}
