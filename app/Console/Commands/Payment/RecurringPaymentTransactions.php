<?php

namespace App\Console\Commands\Payment;

use App\Events\Subscription\PaymentFailedEvent;
use App\Models\Plan;
use App\Models\RecurringTransaction;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Services\Payment\PaymentProcessingService;
use App\Services\Payment\PaymentVerificationService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;

class RecurringPaymentTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recurring:payments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reinitate subscription payments made with card';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $expiredSubscriptionsToday = Subscription::with('transaction.user.cards', 'plan')
            ->bare()
            ->whereDate('expired_at', Carbon::now())
            ->whereNull('suppressed_at')
            ->get();

        foreach ($expiredSubscriptionsToday as $subscription /** @var Subscription $subscription */) {

            /** @var Transaction $formerTransaction */
            $formerTransaction = $subscription->transaction;

            if ($formerTransaction->recurring_usage_exhausted <= $formerTransaction->recurring_ticket_purchased) {

                $recurringTimes = $formerTransaction->recurring_ticket_purchased - $formerTransaction->recurring_usage_exhausted;

                $newTransaction = Transaction::create([
                    'title' => 'Renew Subscription Payments',
                    'actor_id' => $formerTransaction->actor_id,
                    'actor_type' => $formerTransaction->actor_type,
                    'parent_id' => $formerTransaction->id,
                    'user_id' => $formerTransaction->user_id,
                    'subtotal' => $formerTransaction->subtotal,
                    'unit' => $subscription->unit,
                    'total' => $formerTransaction->subtotal,
                    'recurring' => $recurringTimes > 0,
                    'recurring_usage_exhausted' => ++$formerTransaction->recurring_usage_exhausted,
                    'recurring_ticket_purchased' => $formerTransaction->recurring_ticket_purchased,
                    'platform_initiated' => 'Cron',
                    'transactionable_id' => $subscription->plan->id,
                    'transactionable_type' => get_class($subscription->plan),
                ]);

                // Users with no stored card
                if ($subscription->transaction->user->cards->count() < 1) {
                    $this->failedSubscription($subscription);

                    continue;
                }

                $userCardInitiatedPerCard = 0;

                foreach ($subscription->transaction->user->cards as $card /** @var RecurringTransaction $card */) {

                    if (($card->user_id === $formerTransaction->user_id) && ($userCardInitiatedPerCard <= 1)) {
                        $data = (new PaymentVerificationService())->initiateRecurringTransaction(PaymentVerificationService::GATEWAY_PAYSTACK, $newTransaction, $card);

                        try {
                            if (is_array($data) && array_key_exists('success', $data) && $data['success'] === true) {
                                $userCardInitiatedPerCard++;

                                $newTransaction->update(Arr::except($data, ['authorization']));

                                (new PaymentProcessingService())->processAfterSuccess($newTransaction, $subscription->transaction->user);
                            } else {
                                $this->failedSubscription($subscription);
                            }
                        } catch (\Exception) {
                        }
                    }
                }
            }

        }

        return Command::SUCCESS;
    }

    private function failedSubscription(Subscription $subscription): void
    {
        $plan = Plan::where('name', 'Basic')->where('trial', false)->first();
        $subscription->transaction->user->activeTeam->team->subscribeTo($plan);

        event(new PaymentFailedEvent($subscription->transaction));
    }
}
