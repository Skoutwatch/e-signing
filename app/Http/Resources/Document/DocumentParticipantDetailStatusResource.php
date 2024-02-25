<?php

namespace App\Http\Resources\Document;

use App\Http\Resources\SignaturePrint\AppendPrintResource;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentParticipantDetailStatusResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'ip_address' => $this->ip_address,
            'identity_type' => $this->identity_type,
            'national_verification' => $this->national_verification ? true : false,
            'image' => $this->image ? config('externallinks.s3_storage_url').$this->image : null,
            'identity_number' => ($this->identity_type == 'nin' ? $this->nin : ($this->identity_type == 'bvn' ? $this->bvn : ($this->identity_type == 'drivers_license' ? $this->drivers_license_no : null))),
            'prints' => AppendPrintResource::collection($this->prints)->collection->groupBy('type'),
        ];
    }
}
