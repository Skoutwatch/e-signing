<?php

namespace App\Services\ProcessDocument;

use App\Models\Document;
use ErrorException;
use Illuminate\Support\Facades\File;

class FileStorageService
{
    public function fileStorage($file, Document $document): array
    {
        $folderPath = $this->folderPath($document);

        $this->makeDirectory($folderPath);

        $image_parts = explode(';base64,', $file);

        $image_parts_ends = explode(',', $file);

        $image_type = explode('/', mime_content_type($file))[1];

        $image_base64 = base64_decode($image_parts[1]);

        $name = uniqid();

        $filePath = $folderPath.$name.'.'.$image_type;

        file_put_contents($filePath, $image_base64);

        return [
            'type' => $image_type,
            'storage' => $filePath,
            'image_type' => $image_type,
            'name' => $name,
            'base64_file' => $file,
            'base64_type' => $image_parts_ends[0].',',
        ];
    }

    public function folderPath($model): string
    {
        $directory = $model ? config('upload.folder').'/'.strtolower(class_basename($model))."/$model->id/" : throw new ErrorException('Sorry!!! Model Error');
        $this->makeDirectory($directory);

        return $directory;

    }

    public function folderPathWithoutModel(): string
    {
        return config('upload.folder').'/random/';
    }

    public function makeDirectory($file)
    {
        return File::ensureDirectoryExists(public_path($file), 0777, true);
    }

    public function getFileExtensionBase64($file)
    {
        return $file ? explode('/', mime_content_type($file))[1] : throw new ErrorException('Sorry!!! Extenstion not found');
    }

    public function findFileExtension($file)
    {
        return $file ? $file->getClientOriginalExtension() : throw new ErrorException('Sorry!!! Original extenstion not found');
    }
}
