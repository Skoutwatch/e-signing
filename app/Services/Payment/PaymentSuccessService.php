<?php

namespace App\Services\Payment;

use App\Models\DocumentTemplate;
use App\Models\Plan;

class PaymentSuccessService
{
    public function findPaymentModelViaTransaction($transaction)
    {
        return match ($transaction['transactionable_type']) {
            'Plan' => Plan::where('id', $transaction['transactionable_id'])->first(),
            'DocumentTemplate' => DocumentTemplate::where('id', $transaction['transactionable_id'])->first(),
            default => null
        };
    }

    public function findModel($transaction)
    {
        if (class_basename($this->findPaymentModelViaTransaction($transaction)) == 'Plan') {
            auth('api')->user()->activeTeam->team->subscriber->subscribeTo($transaction, expiration: today()->addMonth(1));
        } elseif (class_basename($this->findPaymentModelViaTransaction($transaction)) == 'DocumentTemplate') {
        }
    }
}
