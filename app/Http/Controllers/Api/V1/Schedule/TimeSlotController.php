<?php

namespace App\Http\Controllers\Api\V1\Schedule;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Carbon\CarbonInterval;

class TimeSlotController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/time-slots",
     *      operationId="timeslots",
     *      tags={"Schedule"},
     *      summary="Get a time slots",
     *      description="get a time slots",
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
        $slots = CarbonInterval::minutes(15)->toPeriod('9:00', '17:00');

        return $slotCollection = collect($slots)->map(function ($time) {
            return Carbon::parse($time)->format('H:i:s');
        });

        return $slotCollection;
    }
}
