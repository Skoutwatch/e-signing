<?php

namespace App\Http\Resources\Document;

use Illuminate\Http\Resources\Json\JsonResource;

class DocumentParticipantResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'sent_notification' => $this->notification_count,
            'user' => $this->user?->id ? new DocumentParticipantDetailStatusResource($this->user) : null,
            'iAddedMyselfToDocument' => $this->document?->user_id != $this->user?->id ? false : true,
            'ownerDocument' => $this->document?->user_id != $this->user?->id ? false : true,
            'canEditTool' => $this->document?->user_id != $this->user?->id ? false : true,
            'canDeleteTool' => $this->document?->user_id != $this->user?->id ? false : true,
            'canCreateTool' => $this->document?->user_id != $this->user?->id ? false : true,
            'role' => $this->role,
            'status' => $this->status,
            'sequence_order' => $this->sequence_order,
            'updated_at' => $this->updated_at,

        ];
    }
}
