<?php

namespace App\Traits\Image;

use Aws\Exception\AwsException;
use Illuminate\Support\Facades\Storage;

trait AwsS3
{
    public function storeImage($image)
    {
        try {
            $path = Storage::disk('s3')->put($image, fopen(($image), 'r+'));

            return $image;
        } catch (AwsException $e) {
            return dd($e->getMessage());
        }
    }

    public function storeImageWithAFile($filename, $file)
    {
        Storage::disk('s3')->put($filename, fopen($file, 'r+'));

        return 'https://tonote-storage.s3.eu-west-3.amazonaws.com'.$filename;
    }

    protected function uploadImage($image, $path, $connection = 's3')
    {
        $path = $image->store($path, $connection);

        return $path;
    }

    protected function delete($image, $path, $connection)
    {
        $file_path = parse_url($path);

        Storage::disk('s3')->delete($file_path);
    }

    public function checkUrlImage($url)
    {
        $s3Url = 'https://tonote-storage.s3.eu-west-3.amazonaws.com'.$url;

        if (filter_var($s3Url, FILTER_VALIDATE_URL) !== false) {
            return true;
        }

        return false;
    }
}
