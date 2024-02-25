<?php

namespace App\Http\Resources\Plan;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PlanBenefitCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => PlanBenefitResource::collection($this->collection),
        ];
    }
}
