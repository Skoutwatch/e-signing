<?php

namespace App\Http\Controllers\Api\V1\Schedule;

use App\Enums\ScheduleSessionStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Schedule\UpdateScheduleMonetaryValueFormRequest;
use App\Http\Resources\Schedule\ScheduleSessionResource;
use App\Models\ScheduleSession;
use App\Services\ScheduleSession\ScheduleSessionCheckMonetaryValueService;
use ErrorException;

class ScheduleSessionMonetaryValueController extends Controller
{
    /**
     * @OA\Put(
     *      path="/api/v1/virtual-session-monetary-value/{id}",
     *      operationId="updateVirtualSessionMonetaryValue",
     *      tags={"Schedule"},
     *      summary="Update VirtualSessionMonetaryValue",
     *      description="Update VirtualSessionMonetaryValue",
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="VirtualSession ID",
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
     *          @OA\JsonContent(ref="#/components/schemas/UpdateScheduleMonetaryValueFormRequest")
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
    public function update(UpdateScheduleMonetaryValueFormRequest $request, $id)
    {
        $scheduleSession = ScheduleSession::find($id);

        $scheduleSession->update($request->only('has_monetary_value'));

        ($scheduleSession->status == ScheduleSessionStatus::Completed) ? (throw new ErrorException('You cannot initiate monetary service at the moment. This document has already been completed.')) : null;

        $documentTotal = (new ScheduleSessionCheckMonetaryValueService())->verifyMonetaryValueStatusAmount($scheduleSession);

        $scheduleSession?->transactions?->first()->update([
            'subtotal' => $documentTotal,
            'total' => $documentTotal,
        ]);

        $session = ScheduleSession::with(
            'user',
            'notary',
            'transactions',
            'schedule',
            'schedule.uploads',
            'schedule.participants')->find($scheduleSession->id);

        return new ScheduleSessionResource($session);
    }
}
