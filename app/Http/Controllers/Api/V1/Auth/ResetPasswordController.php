<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Models\User;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    /**
     * @OA\Post(
     *      path="/api/v1/user/password/reset",
     *      operationId="resetPasswordFromToken",
     *      tags={"Authentication"},
     *      summary="reset a registered user password",
     *      description="Returns a registered user reset email",
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(ref="#/components/schemas/ResetPasswordRequest")
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
    public function reset(ResetPasswordRequest $request)
    {
        $email = strtolower($request['email']);
        if ($request->validated()) {
            $updatePassword = DB::table('password_resets')
                ->where([
                    'email' => $email,
                    'token' => $request['token'],
                ])->first();

            if (! $updatePassword) {
                return $this->errorResponse('Invalid Token', 422);
            }

            User::where('email', $request->email)->update(
                [
                    'password' => bcrypt($request['password']),
                    'isset_password' => false,
                    'user_access_code' => null,
                    'registration_mode' => null,
                ]
            );

            DB::table('password_resets')->where(['token' => $request['token']])->delete();

            return $this->showMessage('Your password has been changed! . Please login with your email and new password', 200);
        }
    }

    protected function sendResetResponse(Request $request, $response)
    {
        return $this->successResponse(trans($response));
    }

    protected function sendResetFailedResponse(Request $request, $response)
    {
        return $this->errorResponse(trans($response), 422);
    }
}
