<?php

namespace App\Http\Controllers\Api\V1\Compliance;

use App\Http\Controllers\Controller;
use App\Http\Requests\Compliance\UpdateComplianceResponseFormRequest;
use App\Models\ComplianceResponse;
use App\Models\ScheduleSession;

class ComplianceResponseController extends Controller
{
    /**
     * @OA\Put(
     *      path="/api/v1/schedule-compliance-responses/{schedule_session_id}",
     *      operationId="updateComplianceResponse",
     *      tags={"Schedule"},
     *      summary="Update updateComplianceResponse",
     *      description="Update updateComplianceResponse",
     *
     *      @OA\Parameter(
     *          name="schedule_session_id",
     *          description="Sechedule_session ID",
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
     *          @OA\JsonContent(ref="#/components/schemas/UpdateComplianceResponseFormRequest")
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
    public function update(UpdateComplianceResponseFormRequest $request, $id)
    {
        $scheduleSession = ScheduleSession::find($id);

        $previousResponses = ComplianceResponse::where('schedule_id', $scheduleSession->id)->get();

        foreach ($previousResponses as $response) {
            $response->delete();
        }

        foreach ($request['answers'] as $answer) {
            ComplianceResponse::create([
                'compliance_question_id' => $answer['compliance_question_id'],
                'notary_id' => $answer['notary_id'],
                'document_id' => $answer['document_id'],
                'schedule_type' => class_basename($scheduleSession),
                'document_type' => class_basename($scheduleSession->schedule),
                'schedule_id' => $answer['schedule_id'],
                'answer' => $answer['answer'],
            ]);
        }

        $scheduleSession->update(['compliance_required' => false]);

        return $this->showMessage('Compliance updated');
    }
}
