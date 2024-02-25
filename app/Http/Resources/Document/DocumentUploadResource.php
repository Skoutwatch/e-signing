<?php

namespace App\Http\Resources\Document;

use Illuminate\Http\Resources\Json\JsonResource;

class DocumentUploadResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'file' => config('externallinks.s3_storage_url').$this->file,
            'file_url' => config('externallinks.s3_storage_url').$this->file,
            'status' => $this->status,
            'number_ordering' => $this->number_ordering,
            'tools' => DocumentResourceToolResource::collection($this->whenLoaded('tools')),
            'page_type' => $this->page_type,
            'page_width' => $this->page_width,
            'page_height' => $this->page_height,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
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

    public function checkUrln($url)
    {
        if (filter_var($url, FILTER_VALIDATE_URL) !== false) {
            return $url;
        }

        return config('externallinks.s3_storage_url').$url;
    }
}
