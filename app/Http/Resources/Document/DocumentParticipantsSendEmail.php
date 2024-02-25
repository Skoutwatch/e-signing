<?php

namespace App\Http\Resources\Document;

use Illuminate\Http\Resources\Json\JsonResource;

class DocumentParticipantsSendEmail extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user' => new DocumentParticipantDetailStatusResource($this->user),
            'iAddedMyselfToDocument' => $this->document->user_id != $this->user->id ? false : true,
            'ownerDocument' => $this->document->user_id != $this->user->id ? false : true,
            'canEditTool' => $this->document->user_id != $this->user->id ? false : true,
            'canDeleteTool' => $this->document->user_id != $this->user->id ? false : true,
            'canCreateTool' => $this->document->user_id != $this->user->id ? false : true,
            'role' => $this->role,
        ];
    }
}
