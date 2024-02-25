<?php

namespace App\Services\ProcessDocument;

use App\Models\Document;

class WordToPdfService
{
    public function convertWordToPdfViaShell($file, Document $document): array
    {
        $getFile = $file['storage'];

        $dir = (new FileStorageService())->folderPath($document);

        $getTheOutput = $dir.$file['name'].'.vnd.openxmlformats-officedocument.wordprocessingml.pdf';

        shell_exec("export HOME=/tmp && /usr/bin/soffice --headless --convert-to pdf $getFile --outdir $dir");

        return (new FileStorageService())->fileStorage('data:application/pdf;base64,'.base64_encode(file_get_contents($getTheOutput)), $document);
    }
}
