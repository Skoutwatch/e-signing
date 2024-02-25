<?php

namespace App\Observers\User;

use App\Models\Plan;
use App\Models\TeamUser;
use App\Models\User;
use App\Services\Subscription\SubscriptionRestrictionService;
use App\Services\UserPlanService;

class UserObserver
{
    public function creating(User $user)
    {
        $user->email = strtolower($user->email);
        $user->password = bcrypt($user->password);
    }

    public function created(User $user)
    {
        $user->role_state = $user->role;

        $user->assignRole($user->role);

        $team = $user->team()->create(['user_id' => $user->id]);

        $team->subscribeTo(Plan::where('name', 'Basic')->first());

        $referral = User::find($user->referral_id) ? User::find($user->referral_id)->activeTeam->team->id : null;

        TeamUser::create([
            'team_id' => $team->id,
            'user_id' => $user->id,
            'active' => $referral == null ? 1 : 0,
        ]);

        // (new UserPlanService())->sendEmail($user);

        (new SubscriptionRestrictionService())->consumeFeatureCreate($team, 'Number of Users');
    }

    public function updating(User $user)
    {
        $user->email = strtolower($user->email);
    }

    public function deleted(User $user)
    {
    }

    public function restored(User $user)
    {
    }

    public function forceDeleted(User $user)
    {
    }
}
