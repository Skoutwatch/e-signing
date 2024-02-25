<?php

namespace App\Http\Resources\Payment;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentGatewayListResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'file' => $this->file ? $this->file : 'https://cdn-icons-png.flaticon.com/512/6963/6963703.png',
        ];
    }
}
