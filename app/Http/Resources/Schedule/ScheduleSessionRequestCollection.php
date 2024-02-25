<?php

namespace App\Http\Resources\Schedule;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ScheduleSessionRequestCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection,
        ];
    }

    public static function originalAttribute($index)
    {
        $attribute = [
            'status' => 'status',
            'entry_point' => 'entry_point',
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }

    public static function transformedAttribute($index)
    {
        $attribute = [
            'status' => 'status',
            'entry_point' => 'entry_point',
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }
}
