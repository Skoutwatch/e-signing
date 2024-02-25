<?php

namespace App\Http\Controllers\Api\V1\Team;

use App\Http\Controllers\Controller;
use App\Models\DeletedTeamUser;
use App\Models\TeamUser;
use App\Services\Subscription\SubscriptionRestrictionService;
use App\Services\User\UserService;

class RestoreTeamUserController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/restore-team-user/{id}",
     *      operationId="showRestoreTeamUser",
     *      tags={"Teams"},
     *      summary="Show RestoreTeamUser",
     *      description="Show RestoreTeamUser",
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="User ID",
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
        $remainingUsers = (new SubscriptionRestrictionService())->checkRemainingUsersPaidForToAdd();

        if ($remainingUsers <= 0) {
            return $this->errorResponse('You have reached the maximum user to add in this plan. Please upgrade or pay for more users', 409);
        }

        $deletedTeamUser = DeletedTeamUser::where('user_id', $id)->where('team_id', auth('api')->user()->activeTeam?->team->id)->first();

        $userExist = TeamUser::where('team_id', auth('api')->user()->activeTeam?->team?->id)
            ->where('user_id', $id)
            ->first();

        if ($userExist) {
            $deletedTeamUser ? $deletedTeamUser->delete() : null;

            return $this->errorResponse('Deleted user already exists in your team', 409);
        }

        TeamUser::create([
            'user_id' => $deletedTeamUser->user_id,
            'team_id' => $deletedTeamUser->team_id,
            'permission' => $deletedTeamUser->permission,
            'active' => false,
        ]);

        $deletedTeamUser ? $deletedTeamUser->delete() : throw new \ErrorException('User not found');

        return $this->showAll((new UserService())->userTeamUsers());
    }
}
