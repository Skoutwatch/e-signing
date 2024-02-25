<?php

namespace App\Http\Controllers\Api\V1\Plan;

use App\Http\Controllers\Controller;

class CancelSubscriptionController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/cancel-subscription",
     *      operationId="cancelPlanSubscription",
     *      tags={"Plans"},
     *      summary="cancelPlanSubscription",
     *      description="cancelPlanSubscription",
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
        $current_subscription = (auth('api')->user()->activeTeam?->team?->subscription);

        if (! $current_subscription) {
            $this->errorResponse('You have no available subscription to cancel at the moment. Please contact support', 409);
        }

        return $current_subscription->update(['cancelled_subscription' => true])
            ? $this->showMessage('Your subscription will be cancelled in the next billing cycle')
            : $this->errorResponse('Something went wrong canceling subscription. Please contact support', 409);
    }
}
