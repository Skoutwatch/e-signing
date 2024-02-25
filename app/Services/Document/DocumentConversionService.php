<?php

namespace App\Services\Document;

use App\Models\Document;
use App\Models\DocumentUpload;
use App\Models\User;
use App\Traits\Image\AwsS3;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Lukaswhite\DocumentConverter\Converter;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;
use Spatie\PdfToImage\Pdf;

class DocumentConversionService
{
    use AwsS3;

    public function collectAllRequest($request, Document $document, $status = null)
    {
        $pdfFiles = [];

        $imageFiles = [];

        foreach ($request['files'] as $file) {

            $fileProperty = $this->fileStorage($file, $document);

            match ($fileProperty['type']) {
                'pdf' => $pdfFiles[] = $fileProperty,
                'vnd.openxmlformats-officedocument.wordprocessingml.document' => $pdfFiles[] = $this->matchFiles($fileProperty['type'], $fileProperty, $document),
                'jpg', 'jpeg', 'png' => $imageFiles[] = $fileProperty,
            };
        }

        $pdfCollection = collect($pdfFiles);

        $imageCollection = $imageFiles != [] ? collect([$this->mergeAllImagesToPdf($imageFiles, $document)]) : collect([]);

        $allImages = $imageCollection->pluck('storage')->toArray();

        $allPdfs = $pdfCollection->pluck('storage')->toArray();

        $allPdfMerged = array_merge($allPdfs, $allImages);

        if (! empty($allPdfMerged)) {
            $datadir = $this->folderPath($document);

            $this->makeDirectory($datadir);

            $outputName = "$datadir".Str::slug(uniqid()).'.pdf';

            $cmd = 'gs -q -dNOPAUSE -dBATCH -sDEVICE=pdfwrite -sOutputFile='.$outputName.' '.implode(' ', $allPdfMerged);

            shell_exec($cmd);

            $file = [
                'type' => 'pdf',
                'storage' => $outputName,
                'base64_file' => null,
                'base64_type' => null,
            ];

            return $this->createUpload($file, $document, null, $status);
        }
    }

    public function fileStorage($file, object $document)
    {
        $folderPath = $this->folderPath($document);

        $this->makeDirectory($folderPath);

        $image_parts = explode(';base64,', $file);

        if (count($image_parts) <= 1) {
            return null;
        }

        $image_parts_ends = explode(',', $file);

        $mime_content_type = mime_content_type($file);

        $image_type = explode('/', $mime_content_type)[1];

        $image_base64 = base64_decode($image_parts[1]);

        $filePath = $folderPath.uniqid().'.'.$image_type;

        file_put_contents($filePath, $image_base64);

        return [
            'type' => $image_type,
            'storage' => $filePath,
            'base64_file' => $file,
            'base64_type' => $image_parts_ends[0].',',
        ];
    }

    public function getFileExtensionBase64($file)
    {
        return $file ? explode('/', mime_content_type($file))[1] : null;
    }

    public function findFileExtension($file)
    {
        return $file ? $file->getClientOriginalExtension() : null;
    }

    public function matchFiles($extension, $file, Document $document)
    {
        return match ($extension) {
            'vnd.openxmlformats-officedocument.wordprocessingml.document' => $this->convertWordToPdf($file, $document),
            'pdf', 'jpg', 'jpeg', 'png' => $this->uploadDocument($file, $document),
        };
    }

    public function convertWordToPdfViaLibreOffice($file, Document $document)
    {
        $filename = rand(100000, 9000000);

        $dir = config('upload.folder').'/'.strtolower(class_basename($document))."/$document->id/";

        $path = $dir.$filename.'.pdf';

        $converter = new Converter($file['storage']);

        $time = $converter->outputAs($filename)->outputTo($dir)->toPDF();

        return $this->fileStorage('data:application/pdf;base64,'.base64_encode(file_get_contents($path)), $document);
    }

    public function convertWordToPdf($file, Document $document)
    {
        /* Set the PDF Engine Renderer Path */
        $domPdfPath = base_path('vendor/dompdf/dompdf');

        Settings::setPdfRendererPath($domPdfPath);

        Settings::setPdfRendererName('DomPDF');

        //Load word file
        $Content = IOFactory::load($file['storage']);

        //Save it into PDF
        $PDFWriter = IOFactory::createWriter($Content, 'PDF');

        $dir = $this->folderPath($document);

        $this->makeDirectory($dir);

        $path = $dir.rand(100000, 9000000).'.pdf';

        $PDFWriter->save($path);

        return $this->fileStorage('data:application/pdf;base64,'.base64_encode(file_get_contents($path)), $document);
    }

    public function convertPdfToPng($outputName, $filename, $document, $status = null)
    {
        $outputFileType = 'png';

        $pdf = (new Pdf($outputName))->setOutputFormat($outputFileType);

        foreach (range(1, $pdf->getNumberOfPages()) as $pageNumber) {
            $path = $filename.$pageNumber.uniqid().".$outputFileType";

            $pdf->setPage($pageNumber)->saveImage($path);

            $fileProperty = $this->fileStorage(
                'data:image/'.$outputFileType.';base64,'.base64_encode(file_get_contents($path)),
                $document
            );

            $this->createUpload($fileProperty, $document, null, $status);
        }
    }

    public function uploadDocument($file, $document, $uploadType = null, $status = null, $count = null)
    {
        return $this->createUpload($file, $document, $uploadType, $status, $count);
    }

    public function createUpload($file, Document $document, $uploadType = null, $status = null, $count = null)
    {
        $url = $file['storage'] ? $this->storeImage($file['storage']) : null;

        $upload = DocumentUpload::create([
            'id' => Str::uuid()->toString(),
            'file_url' => $url ? $url : null,
            'file' => $file['storage'],
            'base64_type' => $file ? $file['base64_type'] : null,
            'type' => $file ? $file['type'] : null,
            'document_id' => $document->id,
            'user_id' => auth('api')->user() ? auth('api')->id() : null,
            'status' => $status ? ucfirst($status) : 'Processing',
            'count' => $count,
        ]);

        $document->touch();

        return $upload;
    }

    public function convertUrlPdfToPng($outputName, $filename, $document)
    {
        $item = $this->fileStorage('data:application/pdf;base64,'.base64_encode(file_get_contents($outputName)), $document);

        return $this->convertPdfToPng($item['storage'], $this->folderPath($document).$filename, $document);
    }

    public function storeTemplatePdf($outputName, $document, $status = null)
    {
        $file = [
            'type' => 'pdf',
            'storage' => $outputName,
            'base64_file' => null,
            'base64_type' => null,
        ];

        return $this->createUpload($file, $document, null, $status, null);
    }

    public function convertUrlToFile($outputName, $filename, $document)
    {
        $items = ['files' => []];

        $items['files'][] = 'data:application/pdf;base64,'.base64_encode(file_get_contents($outputName));

        $this->collectAllRequest($items, $document);
    }

    public function storeRequestUploadFiles($request, $document, $status = null)
    {
        $count = 1;

        foreach ($request['files'] as $file) {
            $count = $count + 1;
            $fileProperty = $this->fileStorage($file, $document);

            $this->uploadDocument($fileProperty, $document, 'storage', $status, $count);
        }
    }

    public function storeRequestSingleUploadFiles($upload, Document $document, User $user, $status, $count)
    {
        $file = $this->fileStorage($upload, $document);

        $url = $file['storage'] ? $this->storeImage($file['storage']) : null;

        DocumentUpload::create([
            'id' => Str::uuid()->toString(),
            'file_url' => $url ? $url : null,
            'file' => $file['storage'],
            'base64_type' => $file ? $file['base64_type'] : null,
            'type' => $file ? $file['type'] : null,
            'document_id' => $document->id,
            'user_id' => $user->id,
            'status' => $status ? ucfirst($status) : 'Processing',
            'count' => $count,
        ]);

        $document->touch();
    }

    public function folderPath($model)
    {
        return config('upload.folder').'/'.strtolower(class_basename($model))."/$model->id/";
    }

    public function mergeAllImagesToPdf($files, $document)
    {
        $pdf = new Fpdf;

        foreach ($files as $file) {
            $orientation = null;

            $image = getimagesize($file);
            $imageWidth = $image[0];
            $imageHeight = $image[1];

            if ($imageHeight > $imageWidth) {
                $orientation = 'P';
            } else {
                $orientation = 'L';
            }

            $pdf->AddPage($orientation);
            $pdf->Image($file['storage'], 0, 0, $imageWidth, $imageHeight);
        }

        $path = $this->folderPath($document).rand(100000, 9000000).'.pdf';

        $pdf->Output($path, 'F');

        return [
            'type' => 'pdf',
            'storage' => $path,
            'base64_file' => null,
            'base64_type' => null,
        ];
    }

    public function makeDirectory($file)
    {
        return File::ensureDirectoryExists(($file), 0777, true);
    }
}
