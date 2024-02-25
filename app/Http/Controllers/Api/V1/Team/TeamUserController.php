<?php

namespace App\Http\Controllers\Api\V1\Team;

use App\Http\Controllers\Controller;
use App\Http\Requests\Team\StoreTeamFormRequest;
use App\Models\DeletedTeamUser;
use App\Models\TeamUser;
use App\Models\User;
use App\Services\Mixpanel\MixpanelService;
use App\Services\Subscription\SubscriptionRestrictionService;
use App\Services\User\UserService;

class TeamUserController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/v1/team-users",
     *      operationId="postTeamUser",
     *      tags={"Teams"},
     *      summary="Post TeamUser",
     *      description="Post TeamUser",
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(ref="#/components/schemas/StoreTeamFormRequest")
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
    public function store(StoreTeamFormRequest $request)
    {
        $remainingUsers = (new SubscriptionRestrictionService())->checkRemainingUsersPaidForToAdd();

        if (count($request['team']) <= 0) {
            return $this->errorResponse('You are yet to add any team member', 409);
        }

        if ($remainingUsers <= 0) {
            return $this->errorResponse('You have reached the maximum user to add in this plan. Please upgrade or pay for more users', 409);
        }

        if (count($request['team']) > $remainingUsers) {
            return $this->errorResponse('You cannot add more than the users paid for. Please upgrade or pay for more users', 409);
        }

        if (! auth('api')->user()->isAnAdminInTeam()) {
            return $this->errorResponse('You have no user add right in this team', 409);
        }

        if ($request['team'] !== null || $request['team'] !== []) {

            foreach ($request['team'] as $team) {

                $user = (new UserService())->createOrFindUserIfExist($team, 'team', true);

                if (! $user) {
                    return $this->errorResponse("{$user->first_name} {$user->last_name} cannot be processed to this team. Please try again later. Please remove user", 409);
                }

                if ($user->id == auth('api')->id()) {
                    return $this->errorResponse('You are already added to this team. Please remove your details', 409);
                }

                $userExist = TeamUser::where('team_id', auth('api')->user()->activeTeam?->team?->id)
                    ->where('user_id', $user->id)
                    ->first();

                if ($userExist) {
                    return $this->errorResponse("{$user->first_name} {$user->last_name} User has already been added to this team. Please remove user", 409);
                }

                $teamdetails = TeamUser::create([
                    'user_id' => $user->id,
                    'permission' => $team['permission'],
                    'team_id' => auth('api')->user()->activeTeam?->team?->id,
                    'active' => true,
                ]);

                (new MixpanelService())->teamMembersAdded($team);

                return $this->showMessage('Team member invited successfully');
            }
        }

        return $this->showAll((new UserService())->userTeamUsers());
    }

    /**
     * @OA\Delete(
     *      path="/api/v1/team-users/{id}",
     *      operationId="deleteTeamUsers",
     *      tags={"Teams"},
     *      summary="Delete TeamUsers",
     *      description="Delete TeamUsers",
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="Users ID",
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
    public function destroy($id)
    {
        $user = (User::find($id)) ? (User::find($id)) : (throw new \ErrorException('User Not found'));

        $user->id == auth('api')->id() ? (throw new \ErrorException('You cannot remove yourself from the team')) : null;

        $teamUser = TeamUser::where('team_id', auth('api')->user()->activeTeam?->team?->id)->where('user_id', $user->id)->first();

        $teamUser ? $teamUser : throw new \ErrorException('Cannot find user');
        DeletedTeamUser::create([
            'user_id' => $teamUser->user_id,
            'team_id' => $teamUser->team_id,
            'permission' => $teamUser->permission,
            'active' => false,
        ]);

        $findDeletedTeamUsersSelfCreatedTeam = TeamUser::where('user_id', $id)->where('team_id', $user->team->id)->first();

        $findDeletedTeamUsersSelfCreatedTeam->update(['active' => 1]);

        return $teamUser->delete()
            ? $this->showAll((new UserService())->userTeamUsers())
            : $this->errorResponse('Cannot remove user from the list', 409);
    }
}
