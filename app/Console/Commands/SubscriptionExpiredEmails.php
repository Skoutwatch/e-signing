<?php

namespace App\Console\Commands;

use App\Events\Subscription\SubscriptionExpiredEvent;
use App\Models\Subscription;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class SubscriptionExpiredEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:send-expiry-emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Subscription expired a day ago triggers an event';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $yesterday = Carbon::now()->subDay();

        // Retrieve subscriptions that have expired a day ago
        $subscriptions = Subscription::whereDate('expired_at', $yesterday)->get();

        foreach ($subscriptions as $subscription) {
            event(new SubscriptionExpiredEvent($subscription));
        }

        $this->info('Subscription expiration check completed successfully.');
    }
}
