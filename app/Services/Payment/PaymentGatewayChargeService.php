<?php

namespace App\Services\Payment;

use App\Models\Location\Country;
use App\Models\PaymentGateway;

class PaymentGatewayChargeService
{
    public function __construct(public $total)
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
                'total' => $this->finalChargeAmount($gateway->paymentGatewayList?->name),
            ];
        }

        return $paymentGateways;
    }

    public function gateWayChargePercentage($gateway): float
    {
        return match ($gateway) {
            'Paystack' => $this->total * 0.01,
            'Flutterwave' => $this->total * 0.014,
            'Credo' => $this->total * 0.015,
            default => 0
        };
    }

    public function gateWayChargeAmount($gateway): float
    {
        return match ($gateway) {
            'Paystack' => 100,
            'Flutterwave' => $this->total * 0,
            'Credo' => $this->total * 0,
            default => 0
        };
    }

    public function finalChargeAmount($gateway): float
    {
        return $this->total + ($this->gateWayChargePercentage($gateway) + $this->gateWayChargeAmount($gateway));
    }
}
