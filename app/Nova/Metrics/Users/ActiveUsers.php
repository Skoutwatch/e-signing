<?php

namespace App\Nova\Metrics\Users;

use Carbon\Carbon;
use App\Models\User;
use Laravel\Nova\Metrics\Trend;
use Laravel\Nova\Http\Requests\NovaRequest;

class ActiveUsers extends Trend
{
    /**
     * Calculate the value of the metric.
     *
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
            $range = $request->range;

        switch ($range) {
            case 7:
                return $this->countByDays($request, User::class, 'last_login_activity', 'day', 7);
            case 30:
                return $this->countByDays($request, User::class, 'last_login_activity', 'day', 30);
            case 90:
                $startDate = Carbon::now()->subDays(90)->startOfDay();
                return $this->countByDays($request, User::class, 'last_login_activity', 'day', $startDate);
            case 365:
                return $this->countByDays($request, User::class, 'last_login_activity', 'day', 365);
            default:
                return $this->countByDays($request, User::class, 'last_login_activity');
        }
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges()
    {
        return [
            7 => __('Last 7 Days'),
            30 => __('Last 30 Days'),
            60 => __('60 Days'),
            1 => __('Last Month'),
            3 => __('Last 3 Months'),
            6 => __('Last 6 Months'),
            365 => __('365 Days'),
        ];
    }

    /**
     * Determine the amount of time the results of the metric should be cached.
     *
     * @return \DateTimeInterface|\DateInterval|float|int|null
     */
    public function cacheFor()
    {
        // return now()->addMinutes(5);
    }
}