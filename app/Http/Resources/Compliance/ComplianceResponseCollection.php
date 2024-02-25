<?php

namespace App\Http\Resources\Compliance;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ComplianceResponseCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
