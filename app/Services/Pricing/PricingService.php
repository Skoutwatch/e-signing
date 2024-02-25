<?php

namespace App\Services\Pricing;

use App\Models\Plan;
use App\Models\ScheduleSession;

class PricingService
{
    public function __construct(public ScheduleSession $scheduleRequest, public Plan $plan)
    {
    }

    public function planPrice(): int
    {
        $entryPoint = $this->scheduleRequest?->schedule?->entry_point;
        $requestType = $this->scheduleRequest->request_type;
        $planName = $this->plan->name;
        $isTrial = $this->plan->trial;

        return match (true) {

            $entryPoint == 'Affidavit' && $requestType == 'Custom' && $planName == 'Basic' => $isTrial ? 20000 : 20000,
            $entryPoint == 'Affidavit' && $requestType == 'Custom' && $planName == 'Pro' => $isTrial ? 20000 : 20000,
            $entryPoint == 'Affidavit' && $requestType == 'Custom' && $planName == 'Business' && $isTrial => 20000,
            $entryPoint == 'Affidavit' && $requestType == 'Custom' && $planName == 'Business' && ! $isTrial => 18000,

            $entryPoint == 'Notary' && $planName == 'Basic' && $isTrial => 8000,
            $entryPoint == 'Notary' && $planName == 'Basic' && ! $isTrial => 8000,
            $entryPoint == 'Notary' && $planName == 'Pro' && $isTrial => 8000,
            $entryPoint == 'Notary' && $planName == 'Pro' && ! $isTrial => 8000,
            $entryPoint == 'Notary' && $planName == 'Business' && $isTrial => 8000,
            $entryPoint == 'Notary' && $planName == 'Business' && ! $isTrial => 7200,

            $entryPoint == 'Affidavit' && $requestType != 'Custom' && $planName == 'Basic' && $isTrial => 4000,
            $entryPoint == 'Affidavit' && $requestType != 'Custom' && $planName == 'Basic' && ! $isTrial => 4000,
            $entryPoint == 'Affidavit' && $requestType != 'Custom' && $planName == 'Pro' && $isTrial => 4000,
            $entryPoint == 'Affidavit' && $requestType != 'Custom' && $planName == 'Pro' && ! $isTrial => 4000,
            $entryPoint == 'Affidavit' && $requestType != 'Custom' && $planName == 'Business' && $isTrial => 4000,
            $entryPoint == 'Affidavit' && $requestType != 'Custom' && $planName == 'Business' && ! $isTrial => 3600,

            $entryPoint == 'CFO' => 1000,

            default => 0
        };
    }
}
