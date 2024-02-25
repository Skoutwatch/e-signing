<?php

namespace App\Http\Resources\Plan;

use Illuminate\Http\Resources\Json\JsonResource;

class FreeTrialPlanResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'icon' => $this->icon,
            'description' => $this->description,
            'amount' => (float) $this->amount,
            'periodicity_type' => $this->periodicity_type,
            'trial' => $this->trial ? true : false,
            'next_suggested_plan' => $this->next_suggested_plan,
            'features' => FeatureResource::collection($this->whenLoaded('features')),
            'benefits' => PlanBenefitResource::collection($this->whenLoaded('benefits')),
        ];
    }
}
