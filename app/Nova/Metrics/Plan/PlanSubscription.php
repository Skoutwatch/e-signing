<?php

namespace App\Nova\Metrics\Plan;

use App\Models\Plan;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Partition;

class PlanSubscription extends Partition
{
    public function name()
    {
        return 'All Plans';
    }

    public function calculate(NovaRequest $request)
    {
        $plans = Plan::with('subscriptions')->get();

        return $this->result(
            $plans->flatMap(function ($plan) {
                return [
                    "{$plan->periodicity} {$plan->periodicity_type} {$plan->name} Plan " => $plan->count(),
                ];
            })->toArray()
        );
    }
}
