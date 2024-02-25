<?php

namespace App\Http\Controllers\Api\V1\Notary;

use App\Enums\ScheduleSessionRequestStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Notary\UpdateNotaryRevertStatusFormRequest;
use App\Models\ScheduleSession;
use App\Models\ScheduleSessionRequest;

class ScheduleRequestRevertStatusController extends Controller
{
    public function update($id, UpdateNotaryRevertStatusFormRequest $request)
    {
        $sessionRequest = ScheduleSession::where('id', $request['schedule_session_id'])->get();

        $sessionRequest = ScheduleSessionRequest::where('schedule_session_request_id', $request['schedule_session_id'])->get();

        $sessionRequest->status == $request['status'] ?? throw new \ErrorException('A notary has already '.strtolower($request['status']).' this request');

        if ($request['status'] == 'Rejected') {
            foreach ($sessionRequest->scheduledSession->scheduleSessionRequests as $sessionNotaryRequest) {
                if ($sessionRequest['notary_id'] == auth('api')->id()) {
                    $sessionNotaryRequest->update(['status' => $request['status']]);
                } else {
                    $sessionNotaryRequest->update(['status' => ScheduleSessionRequestStatus::Awaiting]);
                }
            }

            $sessionRequest->scheduledSession->update(['notary_id' => null]);

            $sessionRequest->update(['status' => $request['status']]);
        }

        return $this->showMessage('This request has been '.$request['status']);
    }
}
