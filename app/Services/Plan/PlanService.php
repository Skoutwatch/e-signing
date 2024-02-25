<?php

namespace App\Services\Plan;

class PlanService
{
    public function getUserTeamSubscriptionPlan()
    {
        return auth('api')->user()?->activeTeam?->team?->subscription?->plan;
    }
}
