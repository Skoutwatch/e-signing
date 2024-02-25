<?php

namespace App\Http\Controllers\Api\V1\Plan;

use App\Http\Controllers\Controller;
use App\Models\Plan;

class NotaryPackPlanController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/notary-pack-plans",
     *      operationId="allNotaryPackPlans",
     *      tags={"Plans"},
     *      summary="All NotaryPackPlans",
     *      description="All NotaryPackPlans",
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
        return $this->showAll(Plan::where('periodicity_type', 'Year')->with('features')->get());
    }
}
