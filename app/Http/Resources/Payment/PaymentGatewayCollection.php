<?php

namespace App\Http\Resources\Payment;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PaymentGatewayCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => PaymentGatewayResource::collection($this->collection),
        ];
    }

    public static function originalAttribute($index)
    {
        $attribute = [

        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }

    public static function transformedAttribute($index)
    {
        $attribute = [

        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }
}
