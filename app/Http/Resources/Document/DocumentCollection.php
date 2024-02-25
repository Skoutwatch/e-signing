<?php

namespace App\Http\Resources\Document;

use Illuminate\Http\Resources\Json\ResourceCollection;

class DocumentCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => DocumentResource::collection($this->collection),
        ];
    }

    public static function originalAttribute($index)
    {
        $attribute = [
            'status' => 'status',
            'entry_point' => 'entry_point',
            'updated_at' => 'updated_at',
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }

    public static function transformedAttribute($index)
    {
        $attribute = [
            'status' => 'status',
            'entry_point' => 'entry_point',
            'updated_at' => 'updated_at',
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }
}
