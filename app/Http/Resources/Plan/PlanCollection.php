<?php

namespace App\Http\Resources\Plan;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PlanCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => PlanResource::collection($this->collection),
        ];
    }

    public static function originalAttribute($index)
    {
        $attribute = [
            'periodicity_type' => 'periodicity_type',
            'periodicity' => 'periodicity',
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }

    public static function transformedAttribute($index)
    {
        $attribute = [
            'periodicity_type' => 'periodicity_type',
            'periodicity' => 'periodicity',
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }
}
