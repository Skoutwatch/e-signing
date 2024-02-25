<?php

namespace App\Http\Resources\Signlink;

use Illuminate\Http\Resources\Json\JsonResource;

class SignlinkDocumentToolResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'document_id' => $this->document_id,
            'user_id' => $this->user_id,
            'document_upload_id' => $this->document_upload_id,
            'tool_name' => $this->tool_name,
            'tool_height' => $this->tool_height,
            'tool_width' => $this->tool_width,
            'tool_class' => $this->tool_class,
            'tool_pos_top' => $this->tool_pos_top,
            'tool_pos_left' => $this->tool_pos_left,
            'value_file_url' => config('externallinks.s3_storage_url').$this->value,
            'value' => $this->value,
            'signed' => $this->signed ? true : false,
            'allow_signature' => $this->allow_signature ? true : false,
        ];
    }
}
