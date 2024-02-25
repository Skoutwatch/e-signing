<?php

namespace App\Http\Resources\Plan;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class PlanResource extends JsonResource
{
    public function toArray($request)
    {
        $start_date = Carbon::now();

        $end_date = Carbon::parse(auth('api')->user()->activeTeam?->team?->subscription?->expired_at);

        $end_date = $this->periodicity > 1 && $this->trial == false ? $end_date->addYears(1) : $end_date;

        $difference = $end_date->diffInDays($start_date);

        return [
            'id' => $this->id,
            'name' => $this->name,
            'icon' => $this->icon,
            'unit' => $this->unit,
            'description' => $this->description,
            'amount' => (float) $this->amount,

            'periodicity_type' => $this->periodicity > 1 ? 'Annual' : 'Monthly',

            'periodicity' => $this->periodicity,
            'trial' => $this->trial ? true : false,
            'next_suggested_plan' => $this->next_suggested_plan,

            'features' => FeatureResource::collection($this->whenLoaded('features')),
            'benefits' => PlanBenefitResource::collection($this->whenLoaded('benefits')),

            $this->mergeWhen($this->discount_percentage, [
                'minimum_discount_unit' => $this->minimum_discount_unit,
                'maximum_discount_unit' => $this->maximum_discount_unit,
                'discount_percentage' => (int) $this->discount_percentage,
            ]),

            'started_at' => auth('api')->user()?->activeTeam?->team?->subscription?->started_at,

            'expired_at' => auth('api')->user()?->activeTeam?->team?->subscription?->expired_at,

            'days_remaining' => "$difference days",

            'expired' => auth('api')->user()?->activeTeam?->team?->subscription?->getIsOverdueAttribute(),

            $this->mergeWhen($this->trial, [
                'trial_message' => 'You have '.$difference.' days free trial remaining',
            ]),
        ];
    }

    public function expiryDate()
    {
        Carbon::parse(auth('api')->user()->activeTeam?->team?->subscription?->expired_at);
    }
}
