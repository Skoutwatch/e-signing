<?php

namespace App\Traits\Image;

use Illuminate\Support\Facades\File;

class ImageHelper
{
    public static function uploadAnything($file, $pathDirectory)
    {
        $image = $file;

        $filename = rand(100000, 9000000).'.'.$image->getClientOriginalExtension();

        $directory = $pathDirectory.'/';

        $path = $directory.$filename;

        File::ensureDirectoryExists($directory, $mode = 0777, true);

        $image->move($directory, $filename);

        return [
            'path' => $path,
            'type' => $image->getClientOriginalExtension(),
        ];
    }

    public static function deleteAnything($path)
    {
        return File::delete(public_path($path));

        if (File::exists(public_path($path))) {
            File::delete(public_path($path));
        }
    }

    public static function uploadbase64($file, $extension)
    {
        $directory = "uploads/documents/$document->id/";

        File::ensureDirectoryExists($directory, $mode = 0777, true);

        $filename = rand(100000, 9000000).'.'.$extension;

        $path = $directory.$filename;

        file_put_contents($path, $filename);

        return [
            'path' => $path,
            'type' => $extension,
        ];
    }
}
