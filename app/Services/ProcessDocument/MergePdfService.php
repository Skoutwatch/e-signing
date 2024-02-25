<?php

namespace App\Services\ProcessDocument;

use App\Models\Document;
use Illuminate\Support\Str;

class MergePdfService
{
    public function mergePdf(Document $document, array $allPdfMerged): string
    {
        $datadir = (new FileStorageService())->folderPath($document);

        (new FileStorageService())->makeDirectory($datadir);

        $outputName = "$datadir".Str::slug(uniqid()).'merge.pdf';

        shell_exec('gs -q -dNOPAUSE -dBATCH -sDEVICE=pdfwrite -sOutputFile='.$outputName.' '.implode(' ', $allPdfMerged));

        return $outputName;
    }
}
