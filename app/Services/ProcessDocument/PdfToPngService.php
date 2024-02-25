<?php

namespace App\Services\ProcessDocument;

use App\Services\Document\DocumentUploadService;
use Spatie\PdfToImage\Pdf;

class PdfToPngService
{
    public function convertPdfToPng($outputName, $filename, $document, $status = null)
    {
        $outputFileType = 'png';

        $pdf = (new Pdf($outputName))->setOutputFormat($outputFileType);

        $files = [];

        foreach (range(1, $pdf->getNumberOfPages()) as $pageNumber) {
            $path = $filename.$pageNumber.uniqid().".$outputFileType";

            $pdf->setPage($pageNumber);

            $pdf->setCompressionQuality(10);

            $pdf->saveImage($path);

            $fileProperty = (new FileStorageService())->fileStorage(
                'data:image/'.$outputFileType.';base64,'.base64_encode(file_get_contents($path)),
                $document
            );

            $files[] = (new DocumentUploadService())->createUpload($fileProperty, $document, null, $status, $pageNumber);
        }

        return $files;
    }

    public function pdfToPng($outputName, $filename, $document, $status = null)
    {
        $outputFileType = 'png';

        $pdf = (new Pdf($outputName))->setOutputFormat($outputFileType);

        $fileProperty = [];

        foreach (range(1, $pdf->getNumberOfPages()) as $pageNumber) {
            $path = $filename.$pageNumber.uniqid().".$outputFileType";

            $pdf->setPage($pageNumber)->saveImage($path);

            $fileProperty[] = (new FileStorageService())->fileStorage(
                'data:image/'.$outputFileType.';base64,'.base64_encode(file_get_contents($path)),
                $document
            );
        }

        return $fileProperty;
    }

    public function pdfToImageSinglePage($fileurl, $model)
    {
        $file = (new FileStorageService())->folderPath($model).uniqid().'.png';
        $pdf = new Pdf($fileurl);
        $pdf->setCompressionQuality(40);
        $pdf->saveImage($file);

        return $file;
    }
}
