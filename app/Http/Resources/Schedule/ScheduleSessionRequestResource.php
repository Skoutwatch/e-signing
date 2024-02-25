<?php

namespace App\Http\Resources\Schedule;

use Illuminate\Http\Resources\Json\JsonResource;

class ScheduleSessionRequestResource extends JsonResource
{
    public function toArray($request)
    {
        $email = auth('api')->user()->email;

        return [
            'id' => $this->id,
            'schedule_session_id' => $this->scheduledSession?->id,
            'document_id' => $this->scheduledSession?->schedule?->id,
            'document_name' => $this->scheduledSession?->schedule?->title,
            'start_time' => $this->scheduledSession?->start_time,
            'date' => $this->scheduledSession?->date,
            'time' => $this->scheduledSession?->time,
            'video_recording_file' => $this->scheduledSession?->video_recording_file,
            'status' => $this->status,
            'schedule_session' => new ScheduleSessionResource($this->whenLoaded('scheduledSession')),
            'link' => $this->status == 'Accepted' ? config('externallinks.verify_session_url')."?e=$email&f={$this->status}&document_id=".$this->scheduledSession?->id : null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
