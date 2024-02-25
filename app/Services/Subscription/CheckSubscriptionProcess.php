<?php

namespace App\Services\Subscription;

use App\Models\DeletedTeamUser;
use App\Models\Plan;
use App\Models\TeamUser;
use App\Models\User;
use App\Services\SubscriptionReserve\SubscriptionReserveService;
use Illuminate\Support\Facades\Log;

class CheckSubscriptionProcess
{
    public function resetSubscriptionIfUserHasNoSubscription()
    {
        $currentTeam = auth('api')->user()?->activeTeam?->team;

        $currentTeamSubscription = auth('api')->user()?->activeTeam?->team?->subscription;

        $intendingPlanIfNoSubcription = Plan::where('name', 'Basic')->where('trial', false)->first();

        ($currentTeamSubscription === null) ? $currentTeam?->subscribeTo($intendingPlanIfNoSubcription) : null;

        $newPlanState = ($currentTeamSubscription?->plan?->name === 'Basic') ? $this->giveUserSubscriptionFromSubscriptionReserve($currentTeam, $currentTeamSubscription) : null;

        Log::debug($newPlanState);

        $newPlanState?->name == 'Basic' ? $this->deleteAllUserExceptOwnerIfUserIsBasic() : null;
    }

    public function deleteAllUserExceptOwnerIfUserIsBasic()
    {
        $teamMembers = User::find(auth('api')->id())->team->users;

        foreach ($teamMembers as $members) {

            if ($members['user_id'] != auth('api')->id()) {

                $teamUser = TeamUser::where('team_id', auth('api')->user()->activeTeam?->team?->id)->where('user_id', $members->user_id)->first();

                ($teamUser === null) ? DeletedTeamUser::create([
                    'user_id' => $members['user_id'],
                    'permission' => $members['permission'],
                    'team_id' => $members['team_id'],
                    'active' => false,
                ]) : null;

                $teamUser ? $teamUser->delete() : null;

            }
        }
    }

    public function giveUserSubscriptionFromSubscriptionReserve($team, $subscription)
    {
        $subscriptionReserve = (new SubscriptionReserveService())->userHasSubscriptionReserve();

        if ($subscriptionReserve && $team) {
            $switchResult = $team->switchTo(
                $subscriptionReserve->plan,
                $subscriptionReserve->transaction,
                $subscriptionReserve->expired_at,
                true
            );

            if ($switchResult) {
                $subscriptionReserve->delete();

                return $subscriptionReserve->plan;
            }
        }
    }
}
