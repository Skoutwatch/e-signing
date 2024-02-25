<?php

namespace App\Services\Document;

use App\Models\DocumentResourceTool;
use App\Services\ProcessDocument\FileStorageService;
use App\Services\ProcessDocument\PdfToPngService;

class DocumentExportService
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

            if (($tool->type == 'Text' && $tool?->value != '') || ($tool->type == 'Text' && $tool?->value != null)) {
                $x = $tool->tool_pos_left;
                $y = $tool->tool_pos_top;
                $width = ($tool->tool_width);
                $height = ($tool->tool_height);
                $setTool .= '
                    <div style="position: absolute; top: '.$y.'px; left: '.$x.'px; z-index: 1; width: '.$width.'px; height: '.$height.'px; color:black; font-weight:500; font-family:Poppins, sans-serif !important; padding: 50px 0 !important">
                        '.$tool->value.'
                    </div>';
            }

            if (($tool?->type != 'Text' && $tool->value != null) || ($tool?->type != 'Text' && $tool?->value != '')) {
                $fileTool = config('externallinks.s3_storage_url').$tool->value;
                $x = $tool->tool_pos_left;
                $y = $tool->tool_pos_top;
                $width = ($tool->tool_width);
                $height = ($tool->tool_height);
                $setTool .= '
                    <div style="position: absolute; top: '.$y.'px; left: '.$x.'px; z-index: 1; width: '.$width.'px; height: '.$height.'px;">
                        <img src="'.$fileTool.'" style="width: 100%; padding-bottom: 10px !important">
                    </div>';
            }
        }

        $item = '<!DOCTYPE html>
            <html lang="en" style="height: 0;">
            <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>'.$title.'</title>
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
}
