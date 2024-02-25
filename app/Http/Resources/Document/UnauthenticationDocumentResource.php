<?php

namespace App\Http\Resources\Document;

use App\Http\Resources\Signlink\SignlinkDocumentToolResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class UnauthenticationDocumentResource extends JsonResource
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
            'title' => Str::limit($this->title, 40, '...'),
            'documentUploads' => DocumentUploadResource::collection($this->whenLoaded('uploads')),
            'participants' => DocumentParticipantResource::collection($this->whenLoaded('participants')),
            'status' => $this->status,
            'participants_count' => $this->participants_count,
            'parent_id' => $this->parent_id,

            $this->mergeWhen($this->is_a_signlink_docs, [
                'signed_signlink_document' => 0,
                'signlink_tools' => SignlinkDocumentToolResource::collection($this->whenLoaded('signlinkTools')),
            ]),

            'seals_count' => $this->seals_count,
            'tools_count' => $this->tools_count,
            'uploads_count' => $this->uploads_count,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'is_a_signlink_docs' => $this->is_a_signlink_docs ? true : false,
            'public' => $this->public ? true : false,
            'is_a_template' => $this->is_a_template ? true : false,
            'entry_point' => $this->entry_point,
            'completed_file_request' => $this->completedDocument?->file_url ? $this->completedDocument?->file_url : $this->completedDocument?->status,
        ];
    }
}
