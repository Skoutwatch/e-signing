<?php

namespace App\Http\Resources\Signlink;

use App\Http\Resources\Document\DocumentParticipantResource;
use App\Http\Resources\Document\DocumentUploadResource;
use Illuminate\Http\Resources\Json\JsonResource;

class SignlinkDocumentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'documentUploads' => DocumentUploadResource::collection($this->whenLoaded('uploads')),
            'participants' => DocumentParticipantResource::collection($this->whenLoaded('participants')),
            'status' => $this->status,
            'participants_count' => $this->participants_count,
            'response_count' => $this->signlinkResponses->count(),
            'parent_id' => $this->parent_id,
            'signed_signlink_document' => 0,
            'signlink_tools' => SignlinkDocumentToolResource::collection($this->whenLoaded('signlinkTools')),
            'tools_count' => $this->tools_count,
            'uploads_count' => $this->uploads_count,
            'active' => $this->active == 1 ? true : false,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'is_a_signlink_docs' => $this->is_a_signlink_docs,
            'public' => $this->public,
            'is_a_template' => $this->is_a_template,
            'allow_signature' => $this->allow_signature == true ? true : false,
        ];
    }
}
