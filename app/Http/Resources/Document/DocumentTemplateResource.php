<?php

namespace App\Http\Resources\Document;

use Illuminate\Http\Resources\Json\JsonResource;

class DocumentTemplateResource extends JsonResource
{
    public function toArray($request)
    {
        return parent::toArray($request);

        return [
            'id' => $this->id,
            'file' => $this->checkUrl($this->url),
            'title' => $this->title,
            'role' => $this->role,
            'public' => $this->public ? true : false,
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
