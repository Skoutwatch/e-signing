<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Events\Document\DocumentResendAuthOtpEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\StoreDocumentResendOtpFormRequest;
use App\Services\Document\DocumentService;
use App\Services\User\UserService;

class DocumentResendOtpController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/v1/user/document/resend/otp",
     *      operationId="userResendDocumentOTP",
     *      tags={"OTP"},
     *      summary="Resend of a registered user",
     *      description="Resend of a registered user",
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(ref="#/components/schemas/StoreDocumentResendOtpFormRequest")
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
    public function store(StoreDocumentResendOtpFormRequest $request)
    {
        $findUser = (new DocumentService())->userByDocumentIdAndEmail($request['email'], $request['document_id']);

        if (! $findUser) {
            return $this->errorResponse('You are not a participant in this document', 401);
        }

        return (new UserService())->findUserRegistrationOutsideAuth($request, 'documents')->user_access_code != null
            ?
            (event(new DocumentResendAuthOtpEvent((new UserService())->find($request['email']))) ? $this->showMessage('otp sent successfully') : $this->errorResponse('An error occurred', 401))
            : $this->errorResponse('Please authenticate via your password', 401);
    }
}
