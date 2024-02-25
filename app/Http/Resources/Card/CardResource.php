<?php

namespace App\Http\Resources\Card;

use App\Http\Resources\Payment\PaymentGatewayListResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CardResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'card_type' => $this->card_type,
            'last4' => $this->last4,
            'exp_month' => $this->exp_month,
            'exp_year' => $this->exp_year,
            'payment_gateway' => new PaymentGatewayListResource($this->paymentGatewayList),
        ];
    }
}
