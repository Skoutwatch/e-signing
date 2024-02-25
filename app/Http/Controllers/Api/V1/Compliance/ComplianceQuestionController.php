<?php

namespace App\Http\Controllers\Api\V1\Compliance;

use App\Http\Controllers\Controller;
use App\Http\Resources\Compliance\ComplianceQuestionResource;
use App\Models\ComplianceQuestion;
use App\Models\ScheduleSession;

class ComplianceQuestionController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/schedule-compliance-questions/{id}",
     *      operationId="showComplianceQuestion",
     *      tags={"Schedule"},
     *      summary="Show ComplianceQuestion",
     *      description="Show ComplianceQuestion",
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="ScheduleSession ID",
     *          required=true,
     *          in="path",
     *
     *          @OA\Schema(
     *              type="string"
     *          )
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
    public function show($id)
    {
        $session = ScheduleSession::find($id);

        $questions = $session->default_compliance_type == false
            ? $session->complianceQuestions
            : ComplianceQuestion::where('default', true)->where('execute_publicly', true)->get();

        return ComplianceQuestionResource::collection($questions);
    }
}
