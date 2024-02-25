<?php

namespace App\Services\ProcessDocument;

use App\Services\Document\DocumentUploadService;
use Illuminate\Support\Str;
use setasign\Fpdi\Fpdi;

class SplitPdfService
{
    public function splitPdf(string $filename, $model, $status, $orderNumber = 0)
    {
        $pdf = new Fpdi;

        $pageCount = $pdf->setSourceFile($filename);

        $file = pathinfo($filename, PATHINFO_FILENAME);

        for ($i = 1; $i <= $pageCount; $i++) {
            $newPdf = new Fpdi;

            $newPdf->setSourceFile($filename);

            $template = $newPdf->importPage($i);

            $size = $newPdf->getTemplateSize($template);

            $newPdf->AddPage($size['orientation'], [$size['width'], $size['height']]);

            $newPdf->useTemplate($template);

            $newFilename = (new FileStorageService())->folderPath($model).Str::slug(uniqid()).'.pdf';

            $newPdf->output($newFilename, 'F');

            $file = [
                'type' => 'pdf',
                'storage' => $newFilename,
                'base64_file' => null,
                'base64_type' => null,
                'page_height' => $size['height'],
                'page_width' => $size['width'],
                'page_type' => $size['orientation'],
            ];

            (new DocumentUploadService())->createUpload($file, $model, null, $status, $i + $orderNumber);
        }
    }
}
