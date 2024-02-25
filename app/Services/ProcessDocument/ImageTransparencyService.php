<?php

namespace App\Services\ProcessDocument;

class ImageTransparencyService
{
    public function makeImageTransparent($filename): string
    {
        $im = imagecreatefrompng($filename);

        $remove = imagecolorallocate($im, 208, 216, 218);

        imagecolortransparent($im, $remove);

        $dir = (new FileStorageService())->folderPathWithoutModel();

        $fileDirPng = $dir.rand(100000, 9000000).'.png';

        imagepng($im, $fileDirPng);

        imagedestroy($im);

        return $fileDirPng;
    }
}
