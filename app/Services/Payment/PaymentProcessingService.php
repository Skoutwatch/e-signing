<?php

namespace App\Services\Payment;

use App\Models\Plan;
use App\Models\User;
use App\Services\ScheduleSession\ScheduleSessionService;
use App\Services\Subscription\PlanSubscriptionService;

class PaymentProcessingService
{
    public function processAfterSuccess($transaction, ?User $user = null)
    {
        return match ($transaction['transactionable_type']) {
            'Plan' => (new PlanSubscriptionService())
                ->setTransactionModel($transaction)
                ->processSubscriptionPlan(),
            Plan::class => (new PlanSubscriptionService())
                ->setTransactionModel($transaction)
                ->setUserModel($user)
                ->processSubscriptionPlan(),

            'ScheduleSession' => (new ScheduleSessionService())
                ->setTransactionModel($transaction)
                ->processScheduleSession(),

            default => null
        };
    }
}
