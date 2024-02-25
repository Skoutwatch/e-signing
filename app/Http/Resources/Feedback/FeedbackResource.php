<?php

namespace App\Http\Resources\Feedback;

use Illuminate\Http\Resources\Json\JsonResource;

class FeedbackResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'comment' => $this->comment,
            'rating' => (float) $this->rating,
        ];
    }
}
