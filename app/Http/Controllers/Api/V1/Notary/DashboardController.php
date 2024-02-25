<?php

namespace App\Http\Controllers\Api\V1\Notary;

use App\Enums\ScheduleSessionStatus;
use App\Enums\TransactionStatus;
use App\Http\Controllers\Controller;
use App\Models\ScheduleSession;

class DashboardController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/notary/dashboard",
     *      operationId="notaryDashboard",
     *      tags={"Notary"},
     *      summary="Dashboard of a registered notary",
     *      description="Dashboard of a registered notary",
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
        $meetings = 0;

        $meeting_count = $meetings > 0 ? $meetings : 'no';

        $clients = ScheduleSession::where('notary_id', auth('api')->id())->distinct()->get(['customer_id'])->count();

        $notarised_docs = ScheduleSession::where('notary_id', auth('api')->id())->where('status', ScheduleSessionStatus::Completed)->get()->count();

        $pending_docs = ScheduleSession::where('notary_id', auth('api')->id())->where('status', ScheduleSessionStatus::Awaiting)->get()->count();

        $accepted_docs = ScheduleSession::where('notary_id', auth('api')->id())->where('status', ScheduleSessionStatus::Accepted)->get()->count();

        $all_docs = ScheduleSession::where('notary_id', auth('api')->id())->whereHas('transactions', function ($q) {
            $q->where('status', '=', TransactionStatus::Paid);
        })->latest()->get()->count();

        $data = [
            'clients' => $clients,
            'earnings' => 0.00,
            'session_time' => 0,
            'notarised_docs' => $notarised_docs,
            'completed_docs' => $notarised_docs,
            'awaiting_docs' => $pending_docs,
            'accepted_docs' => $accepted_docs,
            'all_docs' => $all_docs,
            'message' => "You have $meeting_count scheduled meetings for today",
        ];

        return $this->showMessage($data);
    }
}
