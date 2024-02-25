<?php

namespace App\Events\Subscription;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

// use App\Models\Transaction;

class PaymentConfirmation
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $transaction;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($detail)
    {
        $this->transaction = $detail;
    }
}
