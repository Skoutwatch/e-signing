<?php

namespace App\Services\Payment;

use App\Models\Location\Country;
use App\Models\PaymentGateway;
use App\Models\Transaction;

class PaymentGatewayService
{
    public function __construct(public Transaction $transaction)
    {

    }

    public function allFinalGateWayChargeList()
    {
        $paymentGateways = [];

        $gateways = PaymentGateway::with('paymentGatewayList', 'country')->where('country_id', Country::where('name', 'Nigeria')->first()->id)->get();

        foreach ($gateways as $gateway) {
            $paymentGateways[] = [
                'id' => $gateway->id,
                'name' => $gateway->paymentGatewayList?->name,
                'file' => $gateway->paymentGatewayList?->file,
                'transaction_reference' => (new PaymentReferenceTransactionService($this->transaction))->getTransactionReference($gateway->paymentGatewayList?->name),
                'total' => (new PaymentGatewayChargeService($this->transaction->total))->finalChargeAmount($gateway->paymentGatewayList?->name),
            ];
        }

        return $paymentGateways;
    }

    public function oneGateWayCharge()
    {

    }
}
