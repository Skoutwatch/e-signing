<?php

namespace App\Http\Resources\Document;

use Illuminate\Http\Resources\Json\ResourceCollection;

class DocumentTemplateCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return parent::toArray($request);
    }

    public static function originalAttribute($index)
    {
        $attribute = [
            'id' => 'id',
            'role' => 'role',
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }

    public static function transformedAttribute($index)
    {
        $attribute = [
            'id' => 'id',
            'role' => 'role',
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }
}
