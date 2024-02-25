<?php

namespace App\Http\Resources\Subscription;

use App\Http\Resources\Plan\PlanResource;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
{
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'plan' => new PlanResource($this->whenLoaded('plan')),
        ];
    }
}
