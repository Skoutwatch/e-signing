<?php

namespace App\Http\Controllers\Api\V1\Document;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\StoreDocumentVerificationFormRequest;
use App\Models\User;
use App\Services\Document\DocumentService;

class DocumentCreatePasswordController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/v1/document-create-password",
     *      operationId="userDocumentCreatePassword",
     *      tags={"Documents"},
     *      summary="Create password of a registered user",
     *      description="Create password of a registered user",
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

        $user = User::where('email', $request['email'])->first();

        $user->update([
            'password' => bcrypt($request['password']),
            'isset_password' => true,
        ]);

        if (! $token = auth('api')->attempt(['email' => strtolower($request['email']), 'password' => $request['password']])) {
            return $this->authErrorResponse('Invalid email or password', 401);
        }

        return $this->respondWithToken($token);
    }
}
