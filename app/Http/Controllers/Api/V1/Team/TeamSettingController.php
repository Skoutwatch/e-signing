<?php

namespace App\Http\Controllers\Api\V1\Team;

use App\Http\Controllers\Controller;
use App\Http\Requests\Team\UpdateTeamSettingFormRequest;
use App\Http\Resources\Team\TeamUserShortDetailsResource;

class TeamSettingController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/v1/team-settings",
     *      operationId="updateTeamSetting",
     *      tags={"Teams"},
     *      summary="updateTeamSetting",
     *      description="updateTeamSetting",
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(ref="#/components/schemas/UpdateTeamSettingFormRequest")
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
    public function store(UpdateTeamSettingFormRequest $request)
    {
        auth('api')->user()->activeTeam->team()->update($request->validated());

        return TeamUserShortDetailsResource::collection((new UserService())->userTeamUsers());
    }
}
