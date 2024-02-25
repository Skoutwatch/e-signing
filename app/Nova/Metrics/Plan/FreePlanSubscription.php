<?php

namespace App\Nova\Metrics\Plan;

use App\Models\Plan;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Partition;

class FreePlanSubscription extends Partition
{
    public function calculate(NovaRequest $request)
    {
        $plans = Plan::where('trial', true)->withCount('subscriptions')->get();

        return $this->result(
            $plans->flatMap(function ($plan) {
                return [
                    $plan->name => $plan->subscriptions_count,
                ];
            })->toArray()
        );
    }

    public function ranges()
    {
        return [
            30 => __('30 Days'),
            60 => __('60 Days'),
            365 => __('365 Days'),
            'TODAY' => __('Today'),
            'MTD' => __('Month To Date'),
            'QTD' => __('Quarter To Date'),
            'YTD' => __('Year To Date'),
            'ALL' => 'All Time',
        ];
    }

    public function cacheFor()
    {
        // return now()->addMinutes(5);
    }
}
