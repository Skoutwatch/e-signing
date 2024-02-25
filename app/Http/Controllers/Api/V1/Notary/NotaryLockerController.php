<?php

namespace App\Http\Controllers\Api\V1\Notary;

use App\Enums\DocumentStatus;
use App\Enums\EntryPoint;
use App\Http\Controllers\Controller;
use App\Http\Requests\Notary\StoreNotaryLockerFormRequest;
use App\Models\Document;
use App\Models\DocumentLocker;
use App\Models\ScheduleSession;
use App\Services\Document\DocumentConversionService;
use App\Services\Document\DocumentService;

class NotaryLockerController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/notary/notary-locker",
     *      operationId="notaryLocker",
     *      tags={"Notary"},
     *      summary="Get notary locker",
     *      description="get notary locker",
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
        $session = ScheduleSession::with('notary', 'user', 'transactions')->where([
            ['notary_id', auth('api')->id()],
            ['status', 'Completed'],
        ])->get()->pluck('schedule_id')->toArray();

        $lockerIds = DocumentLocker::where('user_id', auth('api')->id())->get()->pluck('document_id')->toArray();

        $documentIds = array_merge($session, $lockerIds);

        $documents = Document::whereIn('id', $documentIds)->get();

        return $this->showAll($documents);
    }

    /**
     * @OA\Post(
     *      path="/api/v1/notary/notary-locker",
     *      operationId="postNotaryLocker",
     *      tags={"Notary"},
     *      summary="Post NotaryLocker",
     *      description="Post NotaryLocker",
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(ref="#/components/schemas/StoreNotaryLockerFormRequest")
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
    public function store(StoreNotaryLockerFormRequest $request)
    {
        $documentProperty = [
            'user_id' => auth('api')->id(),
            'title' => $request['title'],
            'public' => true,
            'status' => DocumentStatus::Locked,
            'entry_point' => EntryPoint::Docs,
        ];

        $document = (new DocumentService())->createDocument($documentProperty);

        $request['files'] ? (new DocumentConversionService())->collectAllRequest($request->only('files'), $document, 'Completed') : null;

        $alreadyLocked = auth('api')->user()->locker->where('document_id', $document->id)->first();

        $alreadyLocked == null ? auth('api')->user()->locker()->create([
            'document_id' => $document->id,
        ]) : null;

        return $this->showMessage('Document updated');
    }

    /**
     * @OA\Delete(
     *      path="/api/v1/notary/notary-locker/{id}",
     *      operationId="deleteNotaryLocker",
     *      tags={"Notary"},
     *      summary="Delete NotaryLocker",
     *      description="Delete NotaryLocker",
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="NotaryLocker ID",
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
