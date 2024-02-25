<?php

namespace App\Services\ScheduleSession;

use App\Enums\PriceVariableState;
use App\Models\Plan;
use App\Models\ScheduleSession;
use App\Models\User;
use App\Services\Pricing\PricingService;

class ScheduleSessionCheckMonetaryValueService
{
    public function verifyMonetaryValueStatusAmount(ScheduleSession $scheduleSession): int
    {
        $plan = $this->getUser($scheduleSession)->activeTeam?->team?->subscription?->plan;

        if (! $plan) {
            return 0;
        }

        $total = $this->findPricingForThisSessionBasedOnConditions($scheduleSession, $plan);

        $countFiles = 1;

        return (($scheduleSession->has_monetary_value === true || $scheduleSession->has_monetary_value === 1) && $countFiles)
                ? PriceVariableState::MonetaryValuePrice
                : ($total * $countFiles);
    }

    public function totalDocumentAmountWithMonetaryValue(ScheduleSession $scheduleSession, Plan $plan): int
    {
        $totalDocuments = $this->countDocumentSessionFiles($scheduleSession) - PriceVariableState::MonetaryValueDocumentAllowance;

        $extraDocumentAmount = $totalDocuments > 0 ? ($totalDocuments * $this->findPricingForThisSessionBasedOnConditions($scheduleSession, $plan)) : 0;

        return PriceVariableState::MonetaryValuePrice + $extraDocumentAmount;
    }

    public function findPricingForThisSessionBasedOnConditions(ScheduleSession $scheduleSession, Plan $plan): int
    {
        return (new PricingService($scheduleSession, $plan))->planPrice();
    }

    public function countDocumentSessionFiles(ScheduleSession $scheduleSession): int
    {
        return ($scheduleSession?->schedule?->childrenDocuments?->count() > 0 && ($scheduleSession->entry_point != 'Video'))
                            ? $scheduleSession?->schedule?->childrenDocuments?->count()
                            : 1;
    }

    private function getUser($model): User
    {
        return match (true) {
            class_basename($model) == 'ScheduleSession' => $model->user,
            class_basename($model) == 'Document' => $model->scheduleSession?->user,
        };
    }
}
