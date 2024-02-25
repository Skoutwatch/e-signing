<?php

namespace App\Http\Controllers\Api\V1\Plan;

use App\Http\Controllers\Controller;
use App\Http\Resources\Plan\FreeTrialPlanResource;
use App\Models\Plan;

class TrialPlanController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/trial-plans",
     *      operationId="allTrialPlans",
     *      tags={"Plans"},
     *      summary="All allTrialPlans",
     *      description="All allTrialPlans",
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
     * )
     */
    public function index()
    {
        return FreeTrialPlanResource::collection(Plan::where('trial', true)->with('benefits')->get());
    }
}
