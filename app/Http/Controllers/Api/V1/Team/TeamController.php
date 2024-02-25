<?php

namespace App\Http\Controllers\Api\V1\Team;

use App\Http\Controllers\Controller;
use App\Http\Requests\Team\UpdateTeamFormRequest;
use App\Services\User\UserService;

class TeamController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/teams",
     *      operationId="createTeam",
     *      tags={"Teams"},
     *      summary="Create a new Team",
     *      description="Create a new Team",
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
        return $this->showAll((new UserService())->userTeamUsers());
    }

    /**
     * @OA\Post(
     *      path="/api/v1/teams",
     *      operationId="updateTeam",
     *      tags={"Teams"},
     *      summary="updateTeam",
     *      description="updateTeam",
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(ref="#/components/schemas/UpdateTeamFormRequest")
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
    public function store(UpdateTeamFormRequest $request)
    {
        auth('api')->user()->activeTeam->team()->update($request->validated());

        return $this->showAll((new UserService())->userTeamUsers());
    }
}
