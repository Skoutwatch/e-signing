<?php

namespace App\Http\Controllers\Api\V1\Notary;

use App\Http\Controllers\Controller;

class UserScheduledRequestController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/notary/users-requests",
     *      operationId="allUsersRequest",
     *      tags={"Notary"},
     *      summary="Get all Users Scheduled request",
     *      description="get Users Scheduled request",
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
        return $this->showAll(auth('api')->user()->notaryScheduledSessionRequests);
    }
}
