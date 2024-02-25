<?php

namespace App\Http\Resources\Notary;

use Illuminate\Http\Resources\Json\JsonResource;

class DateScheduleResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'day' => $this->day,
            'date' => $this->date,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
        ];
    }
}
