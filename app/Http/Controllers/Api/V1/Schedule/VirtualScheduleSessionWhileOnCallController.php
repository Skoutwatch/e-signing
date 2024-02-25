<?php

namespace App\Http\Controllers\Api\V1\Schedule;

use App\Http\Controllers\Controller;
use App\Http\Requests\Schedule\UpdateScheduleSessionWhileOnCallRequest;
use App\Mail\Schedule\ScheduleSessionParticipantEmail;
use App\Models\Document;
use App\Models\DocumentParticipant;
use App\Models\ScheduleSession;
use App\Services\Document\DocumentParticipantService;
use App\Services\User\UserService;
use Illuminate\Support\Facades\Mail;

class VirtualScheduleSessionWhileOnCallController extends Controller
{
    /**
     * @OA\Put(
     *      path="/api/v1/request-participants-on-call/{id}",
     *      operationId="updateVirtualSessionParticipantsOnCall",
     *      tags={"Schedule"},
     *      summary="Update irtualSessionParticipantsOnCall",
     *      description="Update VirtualSessionParticipantsOnCall",
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
     *          @OA\JsonContent(ref="#/components/schemas/UpdateScheduleSessionWhileOnCallRequest")
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
    public function update(UpdateScheduleSessionWhileOnCallRequest $request, $id)
    {
        $scheduleSession = ScheduleSession::find($id);

        $document = Document::find($scheduleSession->schedule_id);

        foreach ($request['participants'] as $participant) {

            $user = (new UserService())->createOrFindUserIfExist($participant, 'documents');

            $findParticipant = DocumentParticipant::where('document_id', $document->id)->where('email', $user->email)->first();

            $storedParticipant = $findParticipant ? $findParticipant : (new DocumentParticipantService())->addParticipant($document, $participant, $user);

            Mail::send(new ScheduleSessionParticipantEmail($document, $storedParticipant, $scheduleSession));

        }

        return $this->showMessage('Participant added successfully');
    }
}
