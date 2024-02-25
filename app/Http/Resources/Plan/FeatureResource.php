<?php

namespace App\Http\Resources\Plan;

use Illuminate\Http\Resources\Json\JsonResource;

class FeatureResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'limits' => $this->consumable ?? 'Unlimited',
            'quota' => $this->quota ? true : false,
            'limit_number' => (int) ($this->pivot->charges) > 0 ? (int) ($this->pivot->charges) : 'Unlimited',

            $this->mergeWhen($this->name == 'Number of Envelops', [
                'executed' => $this->executed('Documents'),
                'remaining' => $this->remainingProperty('Documents'),
            ]),

            $this->mergeWhen($this->name != 'Number of Envelops', [
                'executed' => $this->executed('Documents'),
                'remaining' => $this->remainingProperty('Documents'),
            ]),

            $this->mergeWhen($this->name != 'Number of Users', [
                'executed' => $this->executed('Users'),
                'remaining' => auth('api')->user()?->activeTeam?->team?->subscription?->unit ? auth('api')->user()?->activeTeam?->team?->subscription?->unit : 1,
            ]),
        ];
    }

    public function extraCharges($featureName)
    {
        return auth('api')->user()->activeTeam->team->getTicketChargesForAFeature($featureName);
    }

    public function userPlan()
    {
        return auth('api')->user()?->activeTeam?->team?->subscription?->plan?->name;
    }

    public function limitNumber()
    {
        return (int) ($this->pivot->charges) > 0 ? (int) ($this->pivot->charges) : 'Unlimited';
    }

    public function executed($type)
    {
        return match ($type) {
            'Documents' => auth('api')->user()->activeTeam ? auth('api')->user()->envelopsSentAndCompleted->count() : 0,
            'Users' => (auth('api')->user()->activeTeam)->team->users->count() ? (auth('api')->user()->activeTeam)->team->users->count() : 0,
        };
    }

    public function remainingProperty($type)
    {
        return is_int($this->limitNumber()) ? $this->limitNumber() - $this->executed($type) : 'Unlimited';
    }
}
