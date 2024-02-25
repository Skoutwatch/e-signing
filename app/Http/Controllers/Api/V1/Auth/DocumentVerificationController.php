<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\StoreDocumentVerificationFormRequest;
use App\Models\User;
use App\Services\Document\DocumentService;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class DocumentVerificationController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/v1/user/document/verify",
     *      operationId="userVerifyDocumentViaOTP",
     *      tags={"OTP"},
     *      summary="Profile of a registered user",
     *      description="Profile of a registered user",
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(ref="#/components/schemas/StoreDocumentVerificationFormRequest")
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
    public function store(StoreDocumentVerificationFormRequest $request)
    {
        $findUser = (new DocumentService())->userByDocumentIdAndEmaiIncludingOwner($request['email'], $request['document_id']);

        if (! $findUser) {
            return $this->errorResponse('You are not a participant in this document', 401);
        }

        $user = User::where('email', strtolower($request['email']))->first();

        $credentials = match ($user->isset_password) {
            1, true => (new DocumentService())->userByDocumentIdEmailOtp($request['email'], $request['document_id'], $request['password']),
            0, false => auth('api')->attempt(['email' => strtolower($request['email']), 'password' => $request['password']]),
            default => null
        };

        if (! $credentials) {
            return $this->authErrorResponse('Invalid email or password', 401);
        }

        $user = User::where('email', $request['email'])->first();

        $user->update([
            'user_access_code' => null,
        ]);

        $token = JWTAuth::fromUser($user);

        return ($token) ? $this->respondWithToken($token) : $this->errorResponse('Unauthenticated token', 409);
    }
}
