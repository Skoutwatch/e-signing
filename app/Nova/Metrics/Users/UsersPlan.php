<?php

namespace App\Nova\Metrics\Users;

use App\Models\Plan;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Partition;

class UsersPlan extends Partition
{
    public function name()
    {
        return 'Active Plans';
    }

    public function calculate(NovaRequest $request)
    {
        $plans = Plan::where('trial', false)->with(['subscriptions' => function ($q) {
            $q->where('expired_at', null);
        }])->get();

        return $this->result(
            $plans->flatMap(function ($plan) {
                return [
                    "{$plan->periodicity} {$plan->periodicity_type} {$plan->name} Plan " => $plan->paidPlans->count(),
                ];
            })->toArray()
        );
    }
}
