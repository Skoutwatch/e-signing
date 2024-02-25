<?php

namespace App\Events\Billings;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SuccessfulBilling
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $transaction;

    public function __construct($detail)
    {
        $this->transaction = $detail;
    }
}
