<?php

namespace App\Http\Controllers\Api\V1\Notary;

use App\Http\Controllers\Controller;
use App\Http\Requests\Notary\StoreNotaryCalendarScheduleFormRequest;
use App\Models\NotarySchedule;
use Carbon\Carbon;

class NotaryCalendarScheduleController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/notary/calendar",
     *      operationId="notaryCalendar",
     *      tags={"Notary"},
     *      summary="Get notary calendar",
     *      description="get notary calendar",
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
        return $this->showAll(NotarySchedule::where('notary_id', auth()->id())->latest()->get());
    }

    /**
     * @OA\Post(
     *      path="/api/v1/notary/calendar",
     *      operationId="postNotaryCalendar",
     *      tags={"Notary"},
     *      summary="Post postNotaryCalendar",
     *      description="Post postNotaryCalendar",
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(ref="#/components/schemas/StoreNotaryCalendarScheduleFormRequest")
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
    public function store(StoreNotaryCalendarScheduleFormRequest $request)
    {
        $slots = NotarySchedule::where('notary_id', auth('api')->id())->get();

        foreach ($slots as $slot) {
            $slot->delete();
        }

        foreach ($request['calendar'] as $calendar) {
            $date = Carbon::parse($calendar['date']);

            $nextDate = $date->isPast() ? $date->next($calendar['day']) : $date;

            $data = [
                'day' => $calendar['day'],
                'date' => $nextDate,
                'start_time' => $calendar['start_time'],
                'end_time' => $calendar['end_time'],
                'notary_id' => auth('api')->id(),
            ];

            NotarySchedule::create($data);
        }

        return $this->showAll(NotarySchedule::where('notary_id', auth()->id())->latest()->get());
    }

    public function populated()
    {
        $start = Carbon::now()->setDate(2014, 1, 1);
        $end = Carbon::now()->setDate(2015, 1, 1);

        $holidays = [
            Carbon::create(2014, 2, 2),
            Carbon::create(2014, 4, 17),
            Carbon::create(2014, 5, 19),
            Carbon::create(2014, 7, 3),
        ];

        $days = $start->diffInDaysFiltered(function (Carbon $date) use ($holidays) {
            return $date->isWeekday() && ! in_array($date, $holidays);
        }, $end);
    }
}
