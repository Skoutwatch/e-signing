<?php

namespace App\Http\Controllers\Api\V1\Document;

use App\Http\Controllers\Controller;
use App\Models\User;

class DocumentUserStateCheckController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/document-user-check/{email}",
     *      operationId="showDocumentChek",
     *      tags={"Documents"},
     *      summary="Show User Document",
     *      description="Show User Document",
     *
     *      @OA\Parameter(
     *          name="email",
     *          description="User Email",
     *          required=true,
     *          in="path",
     *
     *          @OA\Schema(
     *              type="string"
     *          )
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
     * )
     */
    public function show($email)
    {
        $user = User::where('email', strtolower($email))->whereNull('user_access_code')->whereNull('registration_mode')->first();

        $data = $user ? [
            'isset_password' => $user->isset_password ? true : false,
            'user_exist' => true,
        ] : [
            'isset_password' => false,
            'user_exist' => false,
        ];

        return $this->showMessage($data);
    }
}
