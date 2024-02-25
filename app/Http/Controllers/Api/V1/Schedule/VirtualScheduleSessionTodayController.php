<?php

namespace App\Http\Controllers\Api\V1\Schedule;

use App\Http\Controllers\Controller;
use App\Models\DocumentParticipant;
use App\Models\ScheduleSession;
use Carbon\Carbon;

class VirtualScheduleSessionTodayController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/request-virtual-session-today",
     *      operationId="allVirtualScheduledRequestToday",
     *      tags={"Schedule"},
     *      summary="Get all Scheduled request today",
     *      description="get Scheduled request today",
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
        $documentParticipantInAVideoSession = DocumentParticipant::where('user_id', auth()->id())->pluck('document_id')->toArray();

        $requests = ScheduleSession::whereIn('schedule_id', $documentParticipantInAVideoSession)
            ->whereDate('date', Carbon::today()->format('Y-m-d'))
            ->orderBy('start_time', 'ASC')
            ->get();

        return $this->showAll($requests);
    }
}
