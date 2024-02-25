<?php

namespace App\Services\Document;

use App\Models\Document;
use App\Models\DocumentUpload;
use App\Traits\Image\AwsS3;
use Illuminate\Support\Facades\Storage;

class DocumentUploadService
{
    use AwsS3;

    public function createUpload($file, Document $document, $uploadType = null, $status = null, $orderNumber = null)
    {
        $url = $file['storage'] ? $this->storeImage($file['storage']) : null;

        $user = auth('api')->user() ? auth('api')->id() : null; // incase user is not authenticated for signlink

        return DocumentUpload::create([
            'file_url' => $url ? $url : null,
            'file' => $url ? $url : null,
            'base64_type' => array_key_exists('base64_type', $file) ? $file['base64_type'] : null,
            'type' => array_key_exists('type', $file) ? $file['type'] : null,
            'document_id' => $document->id,
            'parent_id' => $document?->id,
            'user_id' => $user,
            'status' => $status ? ucfirst($status) : 'Processing',
            'number_ordering' => $orderNumber,
            'page_height' => array_key_exists('page_height', $file) ? $file['page_height'] : null,
            'page_width' => array_key_exists('page_width', $file) ? $file['page_width'] : null,
            'page_type' => array_key_exists('page_type', $file) ? $file['page_type'] : null,
            'converted_file' => array_key_exists('storage', $file) ? $file['storage'] : null,
        ]);
    }

    public function updateUpload(DocumentUpload $upload, $file, Document $document, $uploadType = null, $status = null, $orderNumber = null)
    {
        Storage::disk('s3')->exists($upload?->file_url) ? Storage::disk('s3')->delete($upload?->file_url) : null;

        $url = $file['storage'] ? $this->storeImage($file['storage']) : null;

        $user = auth('api')->user() ? auth('api')->id() : null; // incase user is not authenticated for signlink

        $upload->update([
            'file_url' => $url ? $url : null,
            'file' => $url ? $url : null,
            'base64_type' => array_key_exists('base64_type', $file) ? $file['base64_type'] : null,
            'type' => array_key_exists('type', $file) ? $file['type'] : null,
            'document_id' => $document->id,
            'parent_id' => $document?->id,
            'user_id' => $user,
            'status' => $status ? ucfirst($status) : 'Processing',
            'number_ordering' => $orderNumber,
            'page_height' => array_key_exists('page_height', $file) ? $file['page_height'] : null,
            'page_width' => array_key_exists('page_width', $file) ? $file['page_width'] : null,
            'page_type' => array_key_exists('page_type', $file) ? $file['page_type'] : null,
            'converted_file' => array_key_exists('storage', $file) ? $file['storage'] : null,
        ]);

        return $upload;
    }
}
