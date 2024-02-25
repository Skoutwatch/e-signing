<?php

namespace App\Services\Payment;

use App\Models\PaymentGatewayList;
use App\Models\RecurringTransaction;
use App\Models\Transaction;
use App\Traits\Payments\Credo;
use App\Traits\Payments\Flutterwave;
use App\Traits\Payments\Paystack;
use App\Traits\Payments\PlanPayment;
use Illuminate\Support\Arr;

class PaymentVerificationService
{
    public const GATEWAY_PAYSTACK = 'Paystack';

    public const GATEWAY_FLUTTERWAVE = 'Flutterwave';

    public const GATEWAY_CREDO = 'Credo';

    public const GATEWAY_PLAN = 'Plan';

    public function accessPaymentGateway($gateway, $id)
    {
        return match ($gateway) {
            self::GATEWAY_PAYSTACK => (new Paystack())->verify($id),
            self::GATEWAY_FLUTTERWAVE => (new Flutterwave())->verify($id),
            self::GATEWAY_CREDO => (new Credo())->verify($id),
            self::GATEWAY_PLAN => (new PlanPayment())->verify($id),
            default => null
        };
    }

    public function initiateRecurringTransaction(string $gateway, Transaction $transaction, RecurringTransaction $card)
    {
        return match ($gateway) {
            self::GATEWAY_PAYSTACK => (new Paystack())->recurringTransactions($card, $transaction),
            default => null
        };
    }

    public function activateRecurringTransaction($transaction, $recurringTransactions)
    {
        return match ($transaction['transactionable_type']) {
            self::GATEWAY_PLAN => $this->processRecurringTransactions($transaction, $recurringTransactions),
            default => $this->processRecurringTransactions($transaction, $recurringTransactions)
        };
    }

    public function processRecurringTransactions(Transaction $transaction, $recurringTransactionDetails)
    {
        if ($recurringTransactionDetails['channel'] !== 'card') {
            return;
        }

        $paymentGateWay = PaymentGatewayList::where('name', $recurringTransactionDetails['payment_gateway'])->first();

        $queryFields = Arr::only($recurringTransactionDetails, ['bin', 'exp_month', 'exp_year', 'card_type', 'last4', 'payment_gateway']);

        $reoccurTransaction = RecurringTransaction::where($queryFields)->where('user_id', $transaction->user->id)->first();

        $mergeWithUser = array_merge($recurringTransactionDetails, [
            'user_id' => $transaction->user->id,
            'payment_gateway_list_id' => $paymentGateWay ? $paymentGateWay->id : null,
        ]);

        return ($reoccurTransaction === null) ? RecurringTransaction::create($mergeWithUser) : null;
    }
}
