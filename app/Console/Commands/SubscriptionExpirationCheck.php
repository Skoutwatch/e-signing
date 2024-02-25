<?php

namespace App\Console\Commands;

use App\Events\Subscription\SubscriptionExpiring;
use App\Models\Subscription;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class SubscriptionExpirationCheck extends Command
{
    protected $signature = 'subscriptions:check-expiration';

    protected $description = 'Check subscription expirations and trigger events';

    public function handle()
    {
        $today = Carbon::now();

        // Retrieve subscriptions that have either 7 or 1 day remaining until the expiration date
        $subscriptions = Subscription::where(function ($query) use ($today) {
            $query->whereDate('expired_at', $today->copy()->addDays(7))
                ->orWhereDate('expired_at', $today->copy()->addDay());
        })->orderBy('expired_at', 'asc')->get();

        foreach ($subscriptions as $subscription) {
            $end_date = Carbon::parse($subscription->expired_at);
            $remainingDays = $end_date->diffInDays($today);

            if ($remainingDays === 7 || $remainingDays === 1) {
                event(new SubscriptionExpiring($subscription));
            }
        }

        $this->info('Subscription expiration check completed successfully.');
    }
}
