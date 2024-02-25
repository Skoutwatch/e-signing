<?php

namespace App\Services\Payment;

use App\Models\Transaction;
use App\Traits\Payments\Credo;

class PaymentInitializationService
{
    protected $transaction;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    public function initializePayment()
    {
        (new Credo())->initiate($this->transaction);
    }
}
