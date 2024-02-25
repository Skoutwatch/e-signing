<?php

namespace App\Services\SubscriptionReserve;

use App\Models\SubscriptionReserve;
use App\Models\User;
use App\Services\Subscription\SubscriptionService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SubscriptionReserveService
{
    public function reserveUserMonthlySubscriptions($transaction, int $subOrder = 0, ?User $user = null)
    {
        $started_at = (new SubscriptionService())->getUserTeamSubscription()?->created_at;

        $subscriptionOrder = (int) $this->userLastSubcriptionReserve($user)?->subscription_order;

        $recurringOrdering = $subOrder + $subscriptionOrder;

        $i = $subscriptionOrder + 1;

        for ($i; $i < $recurringOrdering; $i++) {
            $this->createSubscription($transaction, $started_at, $i, $user);
        }
    }

    public function reserveUserSubscriptionAtNextBillingCycle($transaction)
    {
        Log::debug($transaction);

        if (($transaction?->next_billing_cycle_date === null)) {
            return;
        }

        $subscriptionOrder = (int) $this->userLastSubcriptionReserve()?->subscription_order;

        $this->createSubscription(
            $transaction,
            $transaction?->next_billing_cycle_date,
            $subscriptionOrder
        );
    }

    public function createSubscription($transaction, $started_at, $subscriptionOrder, ?User $user = null)
    {
        if ($user === null) {
            $user = auth('api')->user();
        }

        SubscriptionReserve::create([
            'user_id' => $user->id,
            'team_id' => $user->activeTeam?->team?->id,
            'plan_id' => $transaction->transactionable->id,
            'transaction_id' => $transaction->id,
            'subscription_order' => $subscriptionOrder + 1,
            'started_at' => Carbon::rawParse($started_at)->addMonths($subscriptionOrder),
            'expired_at' => Carbon::rawParse($started_at)->addMonths($subscriptionOrder + 1),
        ]);
    }

    public function userHasSubscriptionReserve()
    {
        $today = Carbon::today();

        return SubscriptionReserve::where('user_id', auth()->user()->id)
            ->whereDate('expired_at', '>=', $today)
            ->whereDate('started_at', '<=', $today)
            ->first();
    }

    public function userLastSubcriptionReserve(?User $user = null)
    {
        if ($user === null) {
            $user = auth()->user();
        }

        return SubscriptionReserve::where('user_id', $user->id)->orderBy('subscription_order', 'desc')->first();
    }
}
