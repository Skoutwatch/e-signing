<?php

namespace App\Http\Resources\Payment;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentGatewayResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'gateway' => new PaymentGatewayListResource($this->paymentGatewayList),
            $this->mergeWhen(auth('api')->user(), [
                'active' => auth('api')->user()?->userPaymentGateway?->payment_gateway_id == $this->id,
            ]),
        ];
    }
}
