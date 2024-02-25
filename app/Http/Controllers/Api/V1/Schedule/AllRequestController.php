<?php

namespace App\Http\Controllers\Api\V1\Schedule;

use App\Enums\ScheduleSessionType;
use App\Enums\TransactionStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\Document\DocumentUploadRequestResource;
use App\Models\DocumentUpload;

class AllRequestController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/scheduled-requests",
     *      operationId="allScheduledRequest",
     *      tags={"Schedule"},
     *      summary="Get all Scheduled request",
     *      description="get Scheduled request",
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
        $requests = DocumentUpload::whereHas('document', function ($q) {
            $q->where('user_id', '=', auth('api')->id());
        })
            ->whereHas('document.scheduleSession', function ($q) {
                $q->where('type', '=', ScheduleSessionType::RequestAffidavit)->orWhere('type', '=', ScheduleSessionType::RequestANotary);
            })
            ->whereHas('document.scheduleSession.transactions', function ($q) {
                $q->where('status', '=', TransactionStatus::Paid);
            })
            ->latest()->get();

        return DocumentUploadRequestResource::collection($requests);
    }
}
