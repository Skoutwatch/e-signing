<?php

namespace App\Http\Resources\SignaturePrint;

use Illuminate\Http\Resources\Json\JsonResource;

class SignerResource extends JsonResource
{
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
