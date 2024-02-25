<?php

namespace App\Http\Resources\Plan;

use Illuminate\Http\Resources\Json\ResourceCollection;

class FeatureCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => FeatureResource::collection($this->collection),
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
