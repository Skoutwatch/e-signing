<?php

namespace App\Http\Resources\Document;

use App\Http\Resources\SignaturePrint\AppendPrintResource;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentResourceToolResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'append_print' => new AppendPrintResource($this->whenLoaded('appendPrint')),
            'document_id' => $this->document_id,
            'document_upload_id' => $this->document_upload_id,
            'tool_name' => $this->tool_name,
            'tool_height' => $this->tool_height,
            'tool_width' => $this->tool_width,
            'tool_class' => $this->tool_class,
            'tool_pos_top' => $this->tool_pos_top,
            'tool_pos_left' => $this->tool_pos_left,
            'type' => $this->type,
            'category' => $this->category,

            'signed' => $this->signed ? true : false,
            'allow_signature' => $this->allow_signature == true ? true : false,

            $this->mergeWhen($this->type != 'Text', [
                'value' => $this->value,
                'value_file_url' => config('externallinks.s3_storage_url').$this->value,
            ]),

            $this->mergeWhen($this->type == 'Text', [
                'value' => $this->value,
                'value_file_url' => $this->value,
            ]),

            $this->mergeWhen(auth('api')->user(), [
                'can_fill_in_tool' => auth('api')->id() == $this->user_id ? true : false,
                'can_drag_tool' => auth('api')->id() == $this->upload?->document?->user_id ? true : false,
                'can_resize_tool' => auth('api')->id() == $this->upload?->document?->user_id ? true : false,
                'can_delete_tool' => auth('api')->id() == $this->upload?->document?->user_id ? true : false,
                'can_create_tool' => auth('api')->id() == $this->upload?->document?->user_id ? true : false,
            ]),

        ];
    }
}
