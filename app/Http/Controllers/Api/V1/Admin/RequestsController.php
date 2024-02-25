<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\ScheduleSession;

class RequestsController extends controller
{
    public function index()
    {
        $allRequests = ScheduleSession::all();

        $recentRequests = ScheduleSession::select([
            'request_type', 'title', 'schedule_type', 'date', 'start_time',
        ])
            ->latest()
            ->paginate(3);

        $data = [
            'requests' => $allRequests,
            'recent_requests' => $recentRequests,
        ];

        return $this->showMessage($data);
    }

    public function show($scheduleSessionId)
    {
        $scheduleSession = ScheduleSession::with('document.participants')
            ->findOrFail($scheduleSessionId);

        if (! $scheduleSession) {
            return $this->errorResponse('This request could not be found', 404);
        }

        return $this->showOne($scheduleSession);
    }
}
