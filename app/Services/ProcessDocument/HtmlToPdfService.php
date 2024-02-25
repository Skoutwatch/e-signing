<?php

namespace App\Services\ProcessDocument;

use App\Models\DocumentResourceTool;
use HeadlessChromium\BrowserFactory;

class HtmlToPdfService
{
    public function html($upload, $document)
    {
        $url = config('app.url');

        $file = config('externallinks.s3_storage_url').$upload->file_url;

        $storedFiles = (new FileStorageService())->fileStorage('data:application/pdf;base64,'.base64_encode(file_get_contents($file)), $document);

        $pngImages = (new PdfToPngService())->pdfToPng($storedFiles['storage'], rand(100000, 9000000).'.png', $document, 'SinglePDF');

        $file = $url.$pngImages[0]['storage'];

        $setTool = '';

        $title = $document->title;
        $image = getimagesize($file);

        $imageWidth = $image[0] * 0.67170445004;
        $imageHeight = $image[1] * 0.68824228028;

        $page_width = $upload->page_width * 3.7795275591;
        $page_height = $upload->page_height * 3.7795275591;

        $tools = DocumentResourceTool::where('document_upload_id', $upload->id)->get();

        foreach ($tools as $tool) {

            if ((($tool->type == 'Text' || $tool->type == 'Fullname' || $tool->type == 'Date') && ($tool?->value != null || $tool?->value != ''))) {
                $x = $tool->tool_pos_left;
                $y = $tool->tool_pos_top;
                $width = ($tool->tool_width);
                $height = ($tool->tool_height);
                $setTool .= '
                    <div style="position: absolute; top: '.$y.'px; left: '.$x.'px; z-index: 1; width: '.$width.'px; height: '.$height.'px; color:black; font-weight:500; font-family:Poppins, sans-serif !important; ">
                        '.$tool->value.'
                    </div>';
            }

            if ((($tool?->type == 'NotaryTraditionalSeal' || $tool?->type == 'Signature' || $tool?->type == 'Initial' || $tool?->type == 'Photograph' || $tool?->type == 'Photo' || $tool?->type == 'Seal' || $tool?->type == 'Stamp') && ($tool?->value != null || $tool?->value != ''))) {
                $fileTool = config('externallinks.s3_storage_url').$tool->value;
                $x = $tool->tool_pos_left;
                $y = $tool->tool_pos_top;
                $width = ($tool->tool_width);
                $height = ($tool->tool_height);
                $setTool .= '
                    <div style="position: absolute; top: '.$y.'px; left: '.$x.'px; z-index: 1; width: '.$width.'px; height: '.$height.'px;">
                        <img src="'.$fileTool.'" style="width: 100%">
                    </div>';
            }
        }

        $item = '<!DOCTYPE html>
            <html lang="en" style="height: 0;">
            <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>'.$title.'</title>
            <style>
                @page { margin: 0; padding: 0; }
            </style>
            </head>
            <body style="margin: 0; padding: 0;">
                <div style="margin: 0; padding: 0; color:red; background-position: 100% 100%; position: relative; width:'.$page_width.'px; height:'.$page_height.'px">
                    <img src="'.$file.'" style="width: '.$page_width.'px; height: '.$page_height.'px;">
                    '.$setTool.'
                </div>
            </body>
            </html>';

        $dir = (new FileStorageService())->folderPath($document);
        $fileDirHtml = $dir.rand(100000, 9000000).'.html';
        $fileDirPdf = $dir.rand(100000, 9000000).'.pdf';
        file_put_contents($fileDirHtml, $item);
        $cmheight = $imageHeight * 2.54 / 96;
        $cmwidth = $imageWidth * 2.54 / 96;
        shell_exec("wkhtmltopdf --disable-smart-shrinking --page-width $cmwidth"."cm  --page-height $cmheight"."cm -T 0 -B 0 -L 0 -R 0 $fileDirHtml $fileDirPdf");
        $upload->update(['converted_file' => $fileDirPdf]);

        return $fileDirPdf;
    }

    public function dtcHtml($document)
    {
        $participantTd = '';

        $bg = 'https://dev-api.gettonote.com/assets/images/cert-bg.png';

        foreach ($document->participants as $participant) {
            $participantTd .= '<tr style="border-color: inherit; border-style: solid; border-width: 0;">
                <td style="border-width: 1px; padding: 11.52px 32px">
                    <div style="display: flex; flex-wrap: wrap; padding: 11.52px 8px">
                        <div style="flex: 0 0 auto; width: 25%">'.$participant->first_name.' '.$participant->last_name.'</div>
                        <div style="flex: 0 0 auto; width: 75%">'.$participant->role.'</div>
                    </div>
                    <div style="display: flex; flex-wrap: wrap; padding: 11.52px 8px">
                        <div style="flex: 0 0 auto; width: 25%">Email</div>
                        <div style="flex: 0 0 auto; width: 75%; word-wrap: break-word">'.$participant->email.'</div>
                    </div>
                    <div style="display: flex; flex-wrap: wrap; padding: 11.52px 8px">
                        <div style="flex: 0 0 auto; width: 25%">Device IP.</div>
                        <div style="flex: 0 0 auto; width: 75%">'.$participant->user->ip_address.'</div>
                    </div>
                </td>
                <td>
                    <div style="width: 100%; padding: 11.52px 8px">Signature</div>
                    <div style="width: 100%; padding: 11.52px 8px">
                        time
                    </div>
                </td>
            </tr>';
        }

        $item = '<!DOCTYPE html>
        <html lang="en">
        <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>'.$document->title.'</title>
        <style>
            @page { margin: 0; padding: 0; }
        </style>
        </head>
        <body style="margin: 0; padding: 0;">
            <div style="background-image: url('.$bg.'); background-repeat: no-repeat; background-size: 100% 100%; height: 1059px;">
                <div style="padding: 24px">
                    <div style="text-align: center">
                        <h1 style="font-weight: bolder">Digital Transaction Certificate</h1>
                        <p>Document Ref: '.$document->id.'</p>
                    </div>
                    <div>
                        <p style="font-weight: bold; margin: 16px 0">'.$document->title.'</p>
                        <p style="font-weight: bold; margin: 16px 0">'.$document->updated_at.' </p>
                    </div>

                    <table style="border: 1px solid #4b4b4b; border-collapse: collapse; color: #6e6b7b; width: 100%; vertical-align: middle;" cellpadding="0" cellspacing="0">
                        '.$participantTd.'
                    </table>

                    <div style="margin: 32px 0">
                        <h2>How to verify this document:</h2>
                        <p>
                        1. Visit wwww.gettonote.com/verify-document and type in the
                        document id above in the search panel Or.
                        </p>
                        <p>2. Scan the QR Code at the tale end using your mobile device.</p>
                    </div>
                </div>
            </div>
        </body>';

        $dir = (new FileStorageService())->folderPath($document);
        $fileDirHtml = $dir.rand(100000, 9000000).'.html';
        $fileDirPdf = $dir.rand(100000, 9000000).'.pdf';
        file_put_contents($fileDirHtml, $item);
        shell_exec("wkhtmltopdf --disable-smart-shrinking -T 0 -B 0 -L 0 -R 0 $fileDirHtml $fileDirPdf");

        return $fileDirPdf;
    }

    public function manyHTMLtoPDFs($document)
    {
        $url = config('app.url').'/';
        $content = '';
        $participantTd = '';
        $bg = "$url/assets/images/cert-bg.png";

        $preloadResources = [];

        foreach ($document->orderUploads as $upload) {

            $storedFiles = (new FileStorageService())->fileStorage('data:application/pdf;base64,'.base64_encode(file_get_contents(config('externallinks.s3_storage_url').$upload->file_url)), $document);

            $pngImages = (new PdfToPngService())->pdfToPng($storedFiles['storage'], rand(100000, 9000000).'.png', $document, 'SinglePDF');

            $file = $url.$pngImages[0]['storage'];

            $preloadResources[] = [
                'file_url' => $file,
                'tools' => DocumentResourceTool::where('document_upload_id', $upload->id)->get(),
                'page_width' => $upload->page_width * 3.7795275591,
                'page_height' => $upload->page_height * 3.7795275591,
            ];
        }

        foreach ($preloadResources as $preload) {
            $file = $preload['file_url'];
            $tools = $preload['tools'];
            $page_width = $preload['page_width'];
            $page_height = $preload['page_height'];

            $setTool = '';

            foreach ($tools as $tool) {

                if (($tool->type == 'Text' && $tool?->value != '') || ($tool->type == 'Text' && $tool?->value != null)) {
                    $x = $tool->tool_pos_left;
                    $y = $tool->tool_pos_top;
                    $width = ($tool->tool_width);
                    $height = ($tool->tool_height);
                    $setTool .= '
                        <div style="position: absolute; top: '.$y.'px; left: '.$x.'px; z-index: 1;width: '.$width.'px; height: '.$height.'px; color:black; font-weight:500; font-family:Poppins, sans-serif !important;">
                            '.$tool->value.'
                        </div>';
                }

                if ($tool->type != 'Text') {
                    $fileTool = config('externallinks.s3_storage_url').$tool->value;
                    $x = $tool->tool_pos_left;
                    $y = $tool->tool_pos_top;
                    $width = ($tool->tool_width);
                    $height = ($tool->tool_height);
                    $setTool .= '
                        <div style="position: absolute; top: '.$y.'px; left: '.$x.'px; z-index: 1; width: '.$width.'px; height: '.$height.'px;">
                            <img src="'.$fileTool.'" style="width: 100%">
                        </div>';
                }
            }

            $content .= '<div class="new-page">
                <div style="margin: 0; padding: 0; color:red; background-position: 100% 100%; position: relative; width:'.$page_width.'px; height:'.$page_height.'px">
                    <img src="'.$file.'" style="width: '.$page_width.'px; height: '.$page_height.'px;">
                    '.$setTool.'
                </div>
            </div>';

        }

        $item = '<!DOCTYPE html>
            <html lang="en">
            <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <style>
                @page { margin: 0; padding: 0; }
                @media print {
                    .new-page {
                        page-break-before: always;
                    }
                }
            </style>
            <title>'.$document->title.'</title>
            </head>
            <body style="margin:0!important; padding:0!important;">
                '.$content.'
            </body>
            </html>';

        // $pdf = App::make('dompdf.wrapper');
        // $pdf->setPaper(0, 0, 8000, 40000);
        // $pdf->setOption('enable_font_subsetting', true);
        // $pdf->loadHTML($item);
        // return $pdf->stream();

        // $pdf = new ChromePdf('/usr/bin/chromium');

        $dir = (new FileStorageService())->folderPath($document);

        $fileDirPdf = $dir.rand(100000, 9000000).'.pdf';

        // $pdf->output($fileDirPdf);

        // ($pdf->generateFromHtml($item));

        // return $item;

        $browserFactory = new BrowserFactory();

        // starts headless chrome
        $browser = $browserFactory->createBrowser();

        try {
            // creates a new page and navigate to an URL
            $page = $browser->createPage();

            $page->setHtml($item);

            $page->pdf([
                'printBackground' => true,
                'enableImages' => true,
            ])->saveToFile($fileDirPdf);

            // $page->pdf([
            //     'landscape'           => true,             // default to false
            //     'printBackground'     => true,             // default to false
            //     'displayHeaderFooter' => true,             // default to false
            //     'preferCSSPageSize'   => true,             // default to false (reads parameters directly from @page)
            //     'marginTop'           => 0.0,              // defaults to ~0.4 (must be a float, value in inches)
            //     'marginBottom'        => 0,              // defaults to ~0.4 (must be a float, value in inches)
            //     'marginLeft'          => 0,              // defaults to ~0.4 (must be a float, value in inches)
            //     'marginRight'         => 0,              // defaults to ~0.4 (must be a float, value in inches)
            //     'paperWidth'          => $document->page_width * 0.0393701,              // defaults to 8.5 (must be a float, value in inches)
            //     'paperHeight'         => $document->page_height * 0.0393701,              // defaults to 11.0 (must be a float, value in inches)
            //     'headerTemplate'      => '<div>Tonote</div>', // see details above
            //     'footerTemplate'      => '<div>Tonote</div>', // see details above
            //     'scale'               => 1.2,              // defaults to 1.0 (must be a float)
            // ])->saveToFile($fileDirPdf);

            dd($fileDirPdf);
        } finally {
            // bye
            // return ('fail');
            dd($browser->close());
        }

        // $dir = $this->folderPath($document);
        //     $fileDirPdf = $dir.rand(100000, 9000000).'.pdf';
        //     $browserFactory = new BrowserFactory();
        //     $browser = $browserFactory->createBrowser();
        //     $page = $browser->createPage();
        //     $page->setHtml($item);
        //     $page->pdf([
        //         'landscape'             => true,
        //         'printBackground'       => true,
        //         'paperWidth'            => $page_width * 0.0393701,
        //         'paperHeight'           => $page_width * 0.0393701,
        //     ])->saveToFile($fileDirPdf);

        //     $files[] = ($fileDirPdf);

    }

    public function htmlUsingChrome($upload, $document)
    {
        $url = config('app.url');

        $file = config('externallinks.s3_storage_url').$upload->file_url;

        $storedFiles = (new FileStorageService())->fileStorage('data:application/pdf;base64,'.base64_encode(file_get_contents($file)), $document);

        $pngImages = (new PdfToPngService())->pdfToPng($storedFiles['storage'], rand(100000, 9000000).'.png', $document, 'SinglePDF');

        $file = $url.$pngImages[0]['storage'];

        $setTool = '';

        $title = $document->title;
        $image = getimagesize($file);

        $imageWidth = $image[0] * 0.67170445004;
        $imageHeight = $image[1] * 0.68824228028;

        $page_width = $upload->page_width * 3.7795275591;
        $page_height = $upload->page_height * 3.7795275591;

        $tools = DocumentResourceTool::where('document_upload_id', $upload->id)->get();

        foreach ($tools as $tool) {

            if ((($tool->type == 'Text' || $tool->type == 'Fullname' || $tool->type == 'Date') && ($tool?->value != null || $tool?->value != ''))) {
                $x = $tool->tool_pos_left;
                $y = $tool->tool_pos_top;
                $width = ($tool->tool_width);
                $height = ($tool->tool_height);
                $setTool .= '
                    <div style="position: absolute; top: '.($y).'px; left: '.($x + 30).'px; z-index: 1; width: '.$width.'px; height: '.$height.'px; color:black; font-weight:500; font-family:Poppins, sans-serif !important; ">
                        '.$tool->value.'
                    </div>';
            }

            if ((($tool?->type == 'Signature' || $tool?->type == 'Initial' || $tool?->type == 'Photograph' || $tool?->type == 'Photo' || $tool?->type == 'Seal' || $tool?->type == 'Stamp') && ($tool?->value != null || $tool?->value != ''))) {
                $fileTool = config('externallinks.s3_storage_url').$tool->value;
                $x = $tool->tool_pos_left;
                $y = $tool->tool_pos_top;
                $width = ($tool->tool_width);
                $height = ($tool->tool_height);
                $setTool .= '
                    <div style="position: absolute; top: '.$y.'px; left: '.($x).'px; z-index: 1; width: '.$width.'px; height: '.$height.'px;">
                        <img src="'.$fileTool.'" style="width: 100%">
                    </div>';
            }
        }

        $item = '<!DOCTYPE html>
            <html lang="en" style="height: 0;">
            <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>'.$title.'</title>
            <style>
                @page {
                    margin: 0;
                    padding: 0;
                    size: '.$page_width.'px '.$page_height.'px;
                }
            </style>
            </head>
            <body style="margin: 0; padding: 0;">
                <div style="margin: 0; padding: 0; color:red; background-position: 100% 100%; position: relative; width:'.$page_width.'px; height:'.$page_height.'px">
                    <img src="'.$file.'" style="width: '.$page_width.'px; height: '.$page_height.'px;">
                    '.$setTool.'
                </div>
            </body>
            </html>';

        $chromeExec = 'google-chrome';

        $dir = (new FileStorageService())->folderPath($document);

        $outputFile = $dir.rand(100000, 9000000).'.pdf';

        $version = shell_exec($chromeExec.' --version 2>&1');
        if (! strpos($version, 'Google Chrome') === 0) {
            throw new \Exception('Google Chrome not found at: '.$chromeExec);
        }

        $url = 'data:text/html,'.rawurlencode($item);

        $command = sprintf(
            '%s --headless --disable-gpu --print-to-pdf=%s %s 2>&1', $chromeExec, escapeshellarg($outputFile), escapeshellarg($url)
        );
        exec($command);

        $upload->update(['converted_file' => $outputFile]);

        return $outputFile;
    }
}
