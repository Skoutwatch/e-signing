<?php

namespace App\Traits\Payments;

use App\Models\Transaction;

class PlanPayment
{
    public function verify($id)
    {
        $transaction = Transaction::find($id);

        if ($transaction) {
            return [
                'success' => true,
                'payment_gateway' => 'Plan',
                'payment_gateway_json_response' => json_encode($transaction),
                'payment_reference' => $transaction->id,
                'payment_gateway_charge' => 'Plan',
                'payment_gateway_message' => "Transaction successful on Plan {$transaction->upgrade_type} : next amount to be deducted in your next billing cycle is {$transaction->next_billing_cycle_deduction}",
                'payment_gateway_method' => "Plan{$transaction->upgrade_type}",
                'status' => 'Paid',
                'amount_paid' => $transaction->total,
            ];
        } else {
            return [
                'success' => false,
                'payment_gateway' => 'Plan',
                'status' => 'Failed',
                'payment_gateway_json_response' => json_encode($transaction),
                'payment_gateway_message' => "Transaction failed on plan {$transaction->upgrade_type}",
            ];
        }
    }
}
