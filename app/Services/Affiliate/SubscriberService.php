<?php

namespace App\Services\Affiliate;

use App\Models\AffiliateSubscriber;
use App\Models\Plan;

class SubscriberService
{
    /**
     * Get the name of the plan
     */
    public static function planName(AffiliateSubscriber $subscriber): string
    {
        return match ($subscriber->plan_type) {
            Plan::class => $subscriber->plan->name,
            default => '',
        };
    }
}
