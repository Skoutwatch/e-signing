<?php

namespace App\Http\Resources\Schedule;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ScheduleSessionCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return ScheduleSessionResource::collection($this->collection);
    }

    public static function originalAttribute($index)
    {
        $attribute = [
            'title' => 'title',
            'status' => 'status',
            'entry_point' => 'entry_point',
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }

    public static function transformedAttribute($index)
    {
        $attribute = [
            'title' => 'title',
            'status' => 'status',
            'entry_point' => 'entry_point',
        ];

        return isset($attribute[$index]) ? $attribute[$index] : null;
    }
}
