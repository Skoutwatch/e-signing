<?php

namespace App\Http\Resources\SignaturePrint;

use Illuminate\Http\Resources\Json\JsonResource;

class AppendPrintResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'tool_name' => $this->tool_name,
            'file' => $this->checkUrl($this->file),
            'user_id' => $this->user_id,
            'value' => $this->value,
            'category' => $this->category,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }

    public function checkUrl($url)
    {
        if (filter_var($url, FILTER_VALIDATE_URL) !== false) {
            return $url;
        } else {
            return config('externallinks.s3_storage_url').$url;
        }
    }
}
