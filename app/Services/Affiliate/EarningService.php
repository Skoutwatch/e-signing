<?php

namespace App\Services\Affiliate;

use App\Models\AffiliateEarning;
use App\Models\Plan;

class EarningService
{
    /**
     * Get the name of the plan
     */
    public static function payableName(AffiliateEarning $earning): string
    {
        return match ($earning->payable_type) {
            Plan::class => $earning->payable?->name ?? '',
            default => '',
        };
    }
}
