<?php

namespace App\Http\Controllers\Api\V1\Notary;

use App\Enums\ScheduleSessionStatus;
use App\Http\Controllers\Controller;
use App\Models\ScheduleSession;
use App\Models\ScheduleSessionRequest;
use Illuminate\Database\Eloquent\Builder;

class NotaryRequestController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/notary/notary-requests",
     *      operationId="allNotaryScheduledRequest",
     *      tags={"Notary"},
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
        $search = request()->get('search') ? request()->get('search') : null;

        $scheduledRequestsArrays = ScheduleSessionRequest::where('notary_id', auth('api')->id())->where('status', '!=', 'Rejected')->latest()->pluck('scheduled_session_id')->toArray();

        $request = ScheduleSession::query()
            ->whereIn('id', $scheduledRequestsArrays)
            ->when($search, function (Builder $builder, $search) {
                $builder->where('schedule_sessions.title', 'LIKE', "%{$search}%");
            })->whereNot('status', ScheduleSessionStatus::Pending)
            ->latest()
            ->get();

        return $this->showAll($request);
    }

    /**
     * @OA\Get(
     *      path="/api/v1/notary/notary-requests/{id}",
     *      operationId="showNotaryRequest",
     *      tags={"Notary"},
     *      summary="Show showNotaryRequest",
     *      description="Show showNotaryRequest",
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="showNotaryRequest ID",
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
        return $this->showOne(ScheduleSession::with('schedule', 'schedule.participants', 'schedule.uploads')->find($id));
    }
}
