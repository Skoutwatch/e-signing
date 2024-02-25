<?php

namespace App\Services\Document;

use App\Models\Document;
use App\Services\ProcessDocument\FileStorageService;
use App\Services\ProcessDocument\ImageToPdfService;
use App\Services\ProcessDocument\MergePdfService;
use App\Services\ProcessDocument\PdfToPngService;
use App\Services\ProcessDocument\SplitPdfService;
use App\Services\ProcessDocument\WordToPdfService;

class DocumentConvService
{
    public function collectAllRequest($request, Document $document, $status = null, $orderNumber = null)
    {
        $pdfFiles = [];

        $imageFiles = [];

        foreach ($request['files'] as $file) {

            $fileProperty = (new FileStorageService())->fileStorage($file, $document);

            match ($fileProperty['type']) {
                'pdf' => $pdfFiles[] = $fileProperty,
                'vnd.openxmlformats-officedocument.wordprocessingml.document' => $pdfFiles[] = $this->matchFiles($fileProperty['type'], $fileProperty, $document),
                'jpg', 'jpeg', 'png' => $imageFiles[] = $fileProperty,
            };
        }

        $pdfCollection = collect($pdfFiles);

        $imageCollection = $imageFiles != [] ? collect([(new ImageToPdfService())->mergeAllImagesToPdf($imageFiles, $document)]) : collect([]);

        $allPdfMerged = array_merge($imageCollection->pluck('storage')->toArray(), $pdfCollection->pluck('storage')->toArray());

        $mergedDocument = $allPdfMerged ? (new MergePdfService())->mergePdf($document, $allPdfMerged) : null;

        $mergedDocument ? (new SplitPdfService())->splitPdf($mergedDocument, $document, $status, $orderNumber) : null;
    }

    public function matchFiles($extension, $file, Document $document)
    {
        return match ($extension) {
            'vnd.openxmlformats-officedocument.wordprocessingml.document' => (new WordToPdfService())->convertWordToPdfViaShell($file, $document),
            'pdf', 'jpg', 'jpeg', 'png' => $this->uploadDocument($file, $document),
        };
    }

    public function uploadDocument($file, $document, $uploadType = null, $status = null)
    {
        return (new DocumentUploadService())->createUpload($file, $document, $uploadType, $status);
    }

    public function convertUrlPdfToPng($outputName, $filename, $document)
    {
        $item = (new FileStorageService())->fileStorage('data:application/pdf;base64,'.base64_encode(file_get_contents($outputName)), $document);

        return (new PdfToPngService())->convertPdfToPng($item['storage'], (new FileStorageService())->folderPath($document).$filename, $document);
    }

    public function convertUrlToFile($outputName, $filename, $document)
    {
        $items = ['files' => []];

        $items['files'][] = 'data:application/pdf;base64,'.base64_encode(file_get_contents($outputName));

        $this->collectAllRequest($items, $document);
    }
}
