<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\GoogleAuthFormRequest;
use App\Services\Mixpanel\MixpanelService;
use App\Services\User\GoogleAuthenticationService;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class GoogleAuthController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/v1/user/google-login",
     *      operationId="googleSignIn",
     *      tags={"Authentication"},
     *      summary="Sign In a registered user",
     *      description="Returns a newly registered user data",
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(ref="#/components/schemas/GoogleAuthFormRequest")
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
     * )
     */
    public function store(GoogleAuthFormRequest $request)
    {
        $token = null;

        try {
            $user = (new GoogleAuthenticationService())->setUpAuthentication($request);
            $token = JWTAuth::fromUser($user);
        } catch (\Exception $e) {
            $this->errorResponse($e->getMessage(), 409);
        }

        (new MixpanelService())->googleUserLogin($user);

        return ($token) ? $this->respondWithToken($token) : $this->errorResponse('Unauthenticated token', 409);
    }
}
