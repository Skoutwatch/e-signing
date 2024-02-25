<?php

namespace App\Http\Resources\Signlink;

use Illuminate\Http\Resources\Json\JsonResource;

class SignlinkDocumentResponseResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'file' => $this->file,
            'document' => new SignlinkDocumentResource($this->document),
            'file_url' => config('externallinks.s3_storage_url').$this->file_url,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
