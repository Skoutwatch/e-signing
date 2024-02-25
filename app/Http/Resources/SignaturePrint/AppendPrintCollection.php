<?php

namespace App\Http\Resources\SignaturePrint;

use Illuminate\Http\Resources\Json\ResourceCollection;

class AppendPrintCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => AppendPrintResource::collection($this->collection)->collection->groupBy('type'),
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
