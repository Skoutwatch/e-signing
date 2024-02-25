<?php

namespace App\Http\Controllers\Api\V1\Plan;

use App\Http\Controllers\Controller;
use App\Models\Plan;

class PlanController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/plans",
     *      operationId="allPlansActivePlan",
     *      tags={"Plans"},
     *      summary="All Active plans",
     *      description="All Active plans",
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
        $plans = Plan::where('type', 'Subscription')
            ->where('trial', false)
            ->with('features', 'benefits')
            ->get();

        return $this->showAll($plans);
    }
}
