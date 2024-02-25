<?php

namespace App\Http\Resources\Document;

use Illuminate\Http\Resources\Json\JsonResource;

class DocumentAppendPrintResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'tool_appender_id' => $this->tool_appender_id,
            'file' => $this->file,
            'type' => $this->type,
            'category' => $this->category,
            'value' => $this->value,
        ];
    }
}
