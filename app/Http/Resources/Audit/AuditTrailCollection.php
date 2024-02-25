<?php

namespace App\Http\Resources\Audit;

use Illuminate\Http\Resources\Json\ResourceCollection;

class AuditTrailCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return parent::toArray($request);

        // $chunks = AuditTrailResource::collection($this->collection)->chunk(12)->all();

        // foreach ($chunks as $key => $chunk) {
        //     $chunks[$key] = array_values($chunk->toArray());
        // }

        // return [
        //     'data' => $chunks,
        // ];
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
