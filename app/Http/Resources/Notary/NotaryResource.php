<?php

namespace App\Http\Resources\Notary;

use App\Http\Resources\Feedback\FeedbackResource;
use Illuminate\Http\Resources\Json\JsonResource;

class NotaryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'image' => $this->image,
            'gender' => $this->gender,
            'country' => $this->country?->name,
            'state' => $this->state?->name,
            'is_online' => $this->is_online ? true : false,
            'schedules' => DateScheduleResource::collection($this->notaryCalendar),
            'feedbacks' => FeedbackResource::collection($this->feedbacks),
        ];
    }
}
