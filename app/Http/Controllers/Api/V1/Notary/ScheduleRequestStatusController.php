<?php

namespace App\Http\Controllers\Api\V1\Notary;

use App\Enums\ScheduleSessionRequestStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Notary\UpdateNotaryScheduleRequestFormRequest;
use App\Models\Document;
use App\Models\ScheduleSession;
use App\Models\ScheduleSessionRequest;
use App\Models\User;
use App\Services\Document\DocumentParticipantService;

class ScheduleRequestStatusController extends Controller
{
    /**
     * @OA\Put(
     *      path="/api/v1/notary/update-request-status/{id}",
     *      operationId="updateScheduleSession",
     *      tags={"Notary"},
     *      summary="Update status on scheduleSessionrequest",
     *      description="Update status on scheduleSessionrequest",
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="scheduleSessionRequest ID",
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
     *          @OA\JsonContent(ref="#/components/schemas/UpdateNotaryScheduleRequestFormRequest")
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
    public function update($id, UpdateNotaryScheduleRequestFormRequest $request)
    {
        $scheduleSession = ScheduleSession::find($id);

        $sessionRequest = $scheduleSession?->scheduleSessionRequests->where('notary_id', auth('api')->id())->first();

        $sessionRequest?->status == $request['status'] ? (throw new \ErrorException('A notary has already '.strtolower($request['status']).' this request')) : null;

        $document = Document::find($scheduleSession?->schedule?->id);

        if ($request['status'] == ScheduleSessionRequestStatus::Accepted) {

            $findNotary = $scheduleSession->scheduleSessionRequests->where('notary_id', auth('api')->id())->first();

            $participant = [
                'role' => 'Notary',
                'entry_point' => 'Notary',
            ];

            $document->participants->where('user_id', auth('api')->id())->first() ?? (new DocumentParticipantService())->addParticipant($document, $participant, auth('api')->user());

            if ($findNotary == null) {
                ScheduleSessionRequest::create([
                    'notary_id' => auth('api')->id(),
                    'scheduled_session_id' => $scheduleSession->id,
                    'status' => ScheduleSessionRequestStatus::Accepted,
                ]);
            }

            foreach ($scheduleSession->scheduleSessionRequests as $sessionNotaryRequest) {
                if ($sessionRequest['notary_id'] == auth('api')->id()) {
                    $sessionNotaryRequest->update(['status' => ScheduleSessionRequestStatus::Accepted]);
                } else {
                    $sessionNotaryRequest->update(['status' => ScheduleSessionRequestStatus::Closed]);
                }
            }

            $scheduleSession->update([
                'notary_id' => auth('api')->id(),
                'status' => ScheduleSessionRequestStatus::Accepted,
            ]);
        }

        if ($request['status'] == 'Rejected') {

            $this->assignNotariesToSessionRequest($scheduleSession);

            $rejectUser = ScheduleSessionRequest::where('notary_id', auth('api')->id())->where('scheduled_session_id', $scheduleSession->id)->first();

            $rejectUser ? $rejectUser->update([
                'status' => ScheduleSessionRequestStatus::Rejected,
            ]) : null;

            $document
                ? ($document->participants->where('user_id', auth('api')->id())->first() ? $document->participants->where('user_id', auth('api')->id())->first()->delete() : null)
                : null;

            $scheduleSession->update([
                'notary_id' => null,
            ]);
        }

        $sessionRequest->update(['status' => $request['status']]);

        return $this->showMessage('Request '.$request['status']);
    }

    public function notaryOnSystem(array $removeNotaries = [])
    {
        return User::where('role', 'Notary')->whereNotIn('id', $removeNotaries)->get();
    }

    public function assignNotariesToSessionRequest($scheduleSession)
    {
        $notaries = $this->notaryOnSystem();

        foreach ($notaries as $notary) {
            $findIfAlreadyAssigned = ScheduleSessionRequest::where('notary_id', $notary->id)->where('scheduled_session_id', $scheduleSession->id)->first();

            if (! $findIfAlreadyAssigned || empty($findIfAlreadyAssigned)) {
                ScheduleSessionRequest::create([
                    'notary_id' => $notary->id,
                    'scheduled_session_id' => $scheduleSession->id,
                ]);
            }
        }
    }
}
