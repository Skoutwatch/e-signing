<?php

namespace App\Services\ProcessDocument;

use Codedge\Fpdf\Fpdf\Fpdf;

class ImageToPdfService
{
    public function mergeAllImagesToPdf($files, $document): array
    {
        $pdf = new Fpdf;

        foreach ($files as $file) {
            $pdf->AddPage();
            $pdf->Image($file['storage'], 0, 0);
        }

        $path = (new FileStorageService())->folderPath($document).rand(100000, 9000000).'.pdf';

        $pdf->Output($path, 'F');

        return [
            'type' => 'pdf',
            'storage' => $path,
            'base64_file' => null,
            'base64_type' => null,
        ];
    }
}
