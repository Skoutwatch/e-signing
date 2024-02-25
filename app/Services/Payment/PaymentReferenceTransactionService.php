<?php

namespace App\Services\Payment;

use App\Models\Transaction;

class PaymentReferenceTransactionService
{
    public function __construct(public Transaction $transaction)
    {
    }

    public function getTransactionReference($gateway): string
    {
        return match ($gateway) {
            'Credo' => $this->transaction->transaction_reference,
            'Paystack', 'Flutterwave' => $this->transaction->id,
            default => null
        };
    }
}
