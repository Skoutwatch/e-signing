<?php

namespace App\Http\Resources\Notary;

use Illuminate\Http\Resources\Json\ResourceCollection;

class NotaryCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return parent::toArray($request);
    }

    public static function originalAttribute($index)
    {
        $attribute = [
            'country_id' => 'country_id',
            'state_id' => 'state_id',
            'time' => 'time',
            'date' => 'date',
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }

    public static function transformedAttribute($index)
    {
        $attribute = [
            'country_id' => 'country_id',
            'state_id' => 'state_id',
            'time' => 'time',
            'date' => 'date',
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }
}
