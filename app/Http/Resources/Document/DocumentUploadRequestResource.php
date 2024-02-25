<?php

namespace App\Http\Resources\Document;

use Illuminate\Http\Resources\Json\JsonResource;

class DocumentUploadRequestResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => "{$this->document?->title} {$this->count}",
            'type' => $this->document?->scheduleSession?->type,
            'completed_file_request' => $this->checkUrl($this->status),
            'status' => $this->status,
            'created_at' => $this->updated_at,
        ];
    }

    public function checkUrl($url)
    {
        $urlOutput = match ($url) {
            'Processing', 'Awaiting' => null,
            'Completed' => $this->file_url,
            default => null
        };

        if (filter_var($url, FILTER_VALIDATE_URL) !== false && $urlOutput != null) {
            return $url;
        }

        if ($urlOutput != null) {
            return config('externallinks.s3_storage_url').$urlOutput;
        }

        return null;
    }
}
