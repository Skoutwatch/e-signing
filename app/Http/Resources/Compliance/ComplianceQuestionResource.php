<?php

namespace App\Http\Resources\Compliance;

use Illuminate\Http\Resources\Json\JsonResource;

class ComplianceQuestionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'question' => $this->question,
        ];
    }
}
