<?php

namespace App\Http\Controllers\Api\V1\Schedule;

use App\Events\Schedule\ScheduleSessionRecordingEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Schedule\StoreScheduleRecordingSessionRequest;
use App\Http\Requests\Schedule\UpdateScheduleRecordingSessionRequest;
use App\Models\ScheduleSession;
use App\Models\ScheduleSessionRecording;

class ScheduleRecordingSessionController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/v1/schedule-recording-session",
     *      operationId="postScheduleRecordingSession",
     *      tags={"Schedule"},
     *      summary="Post ScheduleRecordingSession",
     *      description="Post ScheduleRecordingSession",
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(ref="#/components/schemas/StoreScheduleRecordingSessionRequest")
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
    public function store(StoreScheduleRecordingSessionRequest $request)
    {
        foreach ($request['urls'] as $url) {
            ScheduleSessionRecording::create([
                'schedule_session_id' => $url['schedule_session_id'],
                'video_recording_file' => $url['video_recording_file'],
            ]);
        }

        return $this->showMessage('File recording has been saved');
    }

    /**
     * @OA\get(
     *      path="/api/v1/schedule-recording-session/{id}",
     *      operationId="showScheduleRecordingSession",
     *      tags={"Schedule"},
     *      summary="Show ScheduleRecordingSession",
     *      description="Show ScheduleRecordingSession",
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
     *     ),
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
        $schedule = ScheduleSession::find($id);

        return $this->showAll($schedule->scheduleSessionRecordings);
    }

    /**
     * @OA\Put(
     *      path="/api/v1/schedule-recording-session/{id}",
     *      operationId="updateScheduleRecordingSession",
     *      tags={"Schedule"},
     *      summary="Update ScheduleRecordingSession",
     *      description="Update ScheduleRecordingSession",
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
     *     ),
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(ref="#/components/schemas/UpdateScheduleRecordingSessionRequest")
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
    public function update(UpdateScheduleRecordingSessionRequest $request, $id)
    {
        $schedule = ScheduleSession::find($id);
        $schedule->update($request->only('video_session_link'));
        (new ScheduleSessionRecordingEvent($schedule));

        return $this->showMessage('Session link has been stored');
    }
}
