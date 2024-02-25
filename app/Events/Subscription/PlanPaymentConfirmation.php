<?php

namespace App\Events\Subscription;

use App\Models\Plan;
use App\Models\Transaction;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PlanPaymentConfirmation
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(public Transaction $transaction, public Plan $plan)
    {
        $this->transaction = $transaction;
        $this->plan = $plan;
    }
}
