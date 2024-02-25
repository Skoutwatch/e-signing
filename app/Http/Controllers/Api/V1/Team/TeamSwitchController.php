<?php

namespace App\Http\Controllers\Api\V1\Team;

use App\Http\Controllers\Controller;
use App\Models\TeamUser;
use App\Services\User\UserService;

class TeamSwitchController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/team-switch/{id}",
     *      operationId="switchTeams",
     *      tags={"Teams"},
     *      summary="Switch Teams",
     *      description="Switch Teams",
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="Teams ID",
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
     *      security={ {"bearerAuth": {}} },
     * )
     */
    public function show($id)
    {
        $makeTeamActive = TeamUser::where('user_id', auth('api')->id())->where('team_id', $id)->first();

        $makeTeamActive->active == 1 ? $this->errorResponse('The team selected is currently active', 409) : null;

        $teamUsers = TeamUser::where('user_id', auth('api')->id())->get();

        foreach ($teamUsers as $team) {
            $findTeam = TeamUser::find($team->id);
            $findTeam->active = 0;
            $findTeam->save();
        }

        $makeTeamActive = TeamUser::where('user_id', auth('api')->id())->where('team_id', $id)->first();

        $makeTeamActive->update(['active' => 1]);

        return $this->showAll((new UserService())->userPropertyTeams(auth('api')->id()), 201);
    }
}
