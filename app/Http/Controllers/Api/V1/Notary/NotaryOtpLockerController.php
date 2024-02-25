<?php

namespace App\Http\Controllers\Api\V1\Notary;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\VerificationFormRequest;
use App\Mail\DocumentLockerOtpVerificationEmail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class NotaryOtpLockerController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/notary/notary-otp-locker",
     *      operationId="notaryOtpLocker",
     *      tags={"Notary"},
     *      summary="Get notary otp locker",
     *      description="get notary otp locker",
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
        $email = auth('api')->user()->email;

        $token = $this->generate_otp($email);

        return Mail::to($email)->send(new DocumentLockerOtpVerificationEmail(['otp' => $token->token, 'email' => $email])) ? response(['status' => true, 'message' => 'Email Sent']) : response(['status' => false, 'message' => 'Error Sending Verification Email, try later']);
    }

    /**
     * @OA\Post(
     *      path="/api/v1/notary/notary-otp-locker",
     *      operationId="lockerVerifyViaOTP",
     *      tags={"Notary"},
     *      summary="Verify Otp of a notaty locker",
     *      description="Verify Otp of a notaty locker",
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(ref="#/components/schemas/VerificationFormRequest")
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
    public function store(VerificationFormRequest $request)
    {
        $response = $this->validate_otp($request->validated());

        if ($response->status == false) {
            return $this->errorResponse($response->message, 409);
        }

        $user = User::where(['email' => $request['email']])->first();

        $user->access_locker_documents = true;

        $user->save();

        return $this->showMessage('Successfully Verified');
    }
}
