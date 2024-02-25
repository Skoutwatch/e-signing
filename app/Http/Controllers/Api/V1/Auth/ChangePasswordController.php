<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePasswordFormRequest;
use App\Models\User;
use Illuminate\Http\Request;

class ChangePasswordController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/v1/user/change/password",
     *      operationId="changepassword",
     *      tags={"Authentication"},
     *      summary="change password for user",
     *      description="change password of registered user data",
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(ref="#/components/schemas/ChangePasswordFormRequest")
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="Successful password change",
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
    public function store(ChangePasswordFormRequest $request)
    {
        User::find(auth('api')->id())->update([
            'password' => bcrypt($request['password']),
            'user_access_code' => null,
            'registration_mode' => null,
            'isset_password' => false,
        ]);

        return $this->showMessage('your password has been changed');
    }
}
