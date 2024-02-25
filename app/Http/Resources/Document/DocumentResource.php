<?php

namespace App\Http\Resources\Document;

use App\Http\Resources\Signlink\SignlinkDocumentToolResource;
use App\Models\Document;
use App\Services\ScheduleSession\ScheduleSessionExtraSeal;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class DocumentResource extends JsonResource
{
    public function toArray($request)
    {
        $extraSeal = [];

        if ($this->scheduleSession?->transactions) {
            $extraSeal = (new ScheduleSessionExtraSeal())->extraSealFromDocumentOrSession(Document::find($this->id));
        }

        return [
            'id' => $this->id,
            'title' => Str::limit($this->title, 40, '...'),
            'child_documents' => DocumentResource::collection($this->whenLoaded('childrenDocuments')),
            'count_child_documents' => $this->childrenDocuments_count,
            'participants' => DocumentParticipantResource::collection($this->whenLoaded('participants')),
            'status' => $this->status,
            'participants_count' => $this->participants_count,
            'parent_id' => $this->parent_id,
            'has_sequence_order' => $this->has_sequence_order ? true : false,

            'documentUploads' => DocumentUploadResource::collection(
                $this->documentUploads->merge($this->allDocumentUploads)
            ),

            'count_document_uploads' => $this->uploads_count,

            $this->mergeWhen($this->is_a_signlink_docs, [
                'signed_signlink_document' => 0,
                'signlink_tools' => SignlinkDocumentToolResource::collection($this->whenLoaded('signlinkTools')),
            ]),

            $this->mergeWhen(auth('api')->user()?->id, [
                'is_the_owner_of_document' => $this->user_id == auth('api')->id() ? true : false,
                'document_owner' => $this->user_id == auth('api')->id() ? 'Me' : $this->user?->first_name.' '.$this->user?->last_name,
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
            'completed_file_request' => $this->completedDocument?->file_url ? config('externallinks.s3_storage_url').$this->completedDocument?->file_url : $this->completedDocument?->status,
            'all_participants_has_signed' => (($this->tools_count >= 1) && ($this->signed_count == $this->tools_count)),
            'signed_signatures' => $this->signed_count,
            $this->mergeWhen($this->scheduleSession?->transactions, $extraSeal),
        ];
    }
}
