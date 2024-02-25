<?php

namespace App\Http\Controllers\Api\V1\Schedule;

use App\Http\Controllers\Controller;
use App\Http\Requests\Schedule\StoreUserScheduleFormRequest;
use App\Models\ScheduleSession;

class UserScheduleSessionController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/schedules",
     *      operationId="createSelfSchedule",
     *      tags={"Schedule"},
     *      summary="Create a new Schedule",
     *      description="Create a new Schedule",
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
        $schedule = auth('api')->user()->scheduledSessions;

        // return $this->showAll(User::where('user_id', auth('')));
    }

    /**
     * @OA\Post(
     *      path="/api/v1/schedules",
     *      operationId="postSchedule",
     *      tags={"Schedule"},
     *      summary="Post Schedule",
     *      description="Post Schedule",
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(ref="#/components/schemas/StoreUserScheduleFormRequest")
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
    public function store(StoreUserScheduleFormRequest $request)
    {
        return $this->showOne(auth('api')->user()->userScheduledSessions()->create($request->validated()));
    }

    /**
     * @OA\Delete(
     *      path="/api/v1/schedules/{id}",
     *      operationId="deleteSchedule",
     *      tags={"Schedule"},
     *      summary="Delete Schedule",
     *      description="Delete Schedule",
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="Schedule ID",
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
        $print = ScheduleSession::findOrFail($id);

        return $print->delete()
                ? $this->showMessage($print->type.' deleted')
                : $this->errorResponse('cannot delete document participant', 404);
    }
}
