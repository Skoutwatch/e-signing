<?php

namespace App\Services\Subscription;

use App\Models\Subscription;

class SubscriptionService
{
    /**
     * Get the subscription associated with the active team of the authenticated API user.
     *
     * @return \App\Models\Subscription The subscription.
     */
    public function getUserTeamSubscription(): Subscription
    {
        return auth('api')->user()?->activeTeam?->team?->subscription;
    }
}
