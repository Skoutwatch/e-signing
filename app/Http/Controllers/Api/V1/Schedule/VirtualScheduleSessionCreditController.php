<?php

namespace App\Http\Controllers\Api\V1\Schedule;

use App\Http\Controllers\Controller;
use App\Http\Requests\Schedule\UpdateVirtualScheduleSessionCreditFormRequest;
use App\Models\User;

class VirtualScheduleSessionCreditController extends Controller
{
    /**
     * @OA\Put(
     *      path="/api/v1/virtual-session-credit/{id}",
     *      operationId="updateVirtualSessionCredit",
     *      tags={"Schedule"},
     *      summary="Update VirtualSessionCredit",
     *      description="Update VirtualSessionCredit",
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
     *          @OA\JsonContent(ref="#/components/schemas/UpdateVirtualScheduleSessionCreditFormRequest")
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
    public function update(UpdateVirtualScheduleSessionCreditFormRequest $request, $id)
    {
        $user = User::find(auth('api')->id());

        $session_credit = $user->session_credit;

        $remaining_session_credit = $session_credit - $request['minutes'];

        return $user->update(['session_credit' => $remaining_session_credit])
            ? $this->showMessage('Session credit deducted')
            : $this->errorResponse('Unable to deduct session credit', 409);
    }
}
