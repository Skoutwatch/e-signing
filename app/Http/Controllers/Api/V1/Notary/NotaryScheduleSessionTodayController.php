<?php

namespace App\Http\Controllers\Api\V1\Notary;

use App\Http\Controllers\Controller;
use App\Models\ScheduleSession;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NotaryScheduleSessionTodayController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/notary/notary-virtual-session-today",
     *      operationId="allNotaryScheduledRequestToday",
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
        $requests = ScheduleSession::query()->where('notary_id', auth('api')->id())
            ->whereDate('date', Carbon::today()->format('Y-m-d'))
            ->orderBy('start_time', 'ASC')
            ->get();

        return $requests;
    }
}
