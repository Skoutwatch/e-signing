<?php

namespace App\Http\Resources\Audit;

use Illuminate\Http\Resources\Json\JsonResource;

class AuditTrailResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'log_name' => $this->log_name,
            'created_at' => $this->created_at,
        ];
    }
}
