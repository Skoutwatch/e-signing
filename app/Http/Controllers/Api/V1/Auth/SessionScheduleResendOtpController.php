<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Events\Schedule\ScheduleResendAuthOtp;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\SessionResendOtpFormRequest;
use App\Models\DocumentParticipant;
use App\Services\Document\DocumentService;
use App\Services\ScheduleSession\ScheduleSessionService;
use App\Services\User\UserService;

class SessionScheduleResendOtpController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/v1/user/session/resend/otp",
     *      tags={"OTP"},
     *      summary="Resend of a registered user",
     *      description="Resend of a registered user",
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(ref="#/components/schemas/SessionResendOtpFormRequest")
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
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      security={ {"bearerAuth": {}} },
     * )
     */
    public function store(SessionResendOtpFormRequest $request)
    {
        $session = (new ScheduleSessionService())->find($request['session_id']);

        if (! $session) {
            return $this->errorResponse('Session ID does not exists', 401);
        }

        $findUser = (new DocumentService())->userByDocumentIdAndEmail($request['email'], $session->schedule_id);

        if (! $findUser) {
            return $this->errorResponse('You are not a participant in this document', 401);
        }

        $user = (new UserService())->find($request['email']);

        $participant = DocumentParticipant::where('user_id', $user->id)->where('document_id', $session->schedule_id)->first();

        return (new UserService())->findUserRegistrationOutsideAuth($request, 'documents')->user_access_code != null
            ?
            (event(new ScheduleResendAuthOtp($participant)) ? $this->showMessage('otp sent successfully') : $this->errorResponse('An error occurred', 401))
            : $this->errorResponse('Please authenticate via your password', 401);
    }
}
