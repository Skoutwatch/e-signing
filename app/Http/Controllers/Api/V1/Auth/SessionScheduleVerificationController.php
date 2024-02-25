<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\SessionOtpVerificationFormRequest;
use App\Models\User;
use App\Services\Document\DocumentService;
use App\Services\ScheduleSession\ScheduleSessionService;

class SessionScheduleVerificationController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/v1/user/ScheduleSession/verify",
     *      operationId="userVerifyScheduleSessionViaOTP",
     *      tags={"OTP"},
     *      summary="Profile of a registered user",
     *      description="Profile of a registered user",
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(ref="#/components/schemas/SessionOtpVerificationFormRequest")
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
    public function store(SessionOtpVerificationFormRequest $request)
    {
        $session = (new ScheduleSessionService())->find($request['session_id']);

        if (! $session) {
            return $this->errorResponse('Session id does not exists', 401);
        }

        $findUser = (new DocumentService())->userByDocumentIdAndEmail($request['email'], $session['schedule_id']);

        if (! $findUser) {
            return $this->errorResponse('You are not a participant in this session', 401);
        }

        if (! $token = auth('api')->attempt($request->only(['email', 'password']))) {
            return $this->authErrorResponse('You cannot participate in this session with this credentials', 401);
        }

        User::find(auth('api')->id())->update([
            'user_access_code' => null,
            'registration_mode' => null,
            'isset_password' => false,
        ]);

        return $this->respondWithToken($token);
    }
}
