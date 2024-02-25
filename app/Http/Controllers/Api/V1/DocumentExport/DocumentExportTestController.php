<?php

namespace App\Http\Controllers\Api\V1\DocumentExport;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Services\Document\DocumentUploadService;
use App\Services\ProcessDocument\HtmlToPdfService;
use App\Services\ProcessDocument\MergePdfService;
use Illuminate\Support\Facades\Storage;

class DocumentExportTestController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/document-export-test/{id}",
     *      operationId="showDocumentExportTest",
     *      tags={"Document"},
     *      summary="Show DocumentExportTest",
     *      description="Show DocumentExportTest",
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="Document Export ID",
     *          required=true,
     *          in="path",
     *
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="Successful signin",
     *
     *          @OA\MediaType(
     *             mediaType="application/json",
     *         ),
     *       ),
     *
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * )
     */
    public function show($id)
    {
        $document = Document::findOrFail($id);

        foreach ($document->orderUploads as $upload) {
            if (file_exists($upload->converted_file)) {
                $files[] = $upload->converted_file;
            } else {
                return $files[] = (new HtmlToPdfService())->htmlUsingChrome($upload, $document);
            }
        }

        $files[] = (new HtmlToPdfService())->dtcHtml($document);

        $mergedFiles = [];

        $mergedFiles['storage'] = (new MergePdfService())->mergePdf($document, $files);

        $document->completedDocument && (filter_var($document->completedDocument->file, FILTER_VALIDATE_URL) !== false)
            ? Storage::disk('s3')->delete($document->completedDocument->file_url) : null;

        $upload = (new DocumentUploadService())->createUpload($mergedFiles, $document, null, 'Completed', null);

        if ($document->status == 'Completed' && ($upload->file != null)) {

            $processedDocuments = $document->uploads->where('status', 'Processed');

            foreach ($processedDocuments as $processedDocument) {
                file_exists($upload->converted_file) ? unlink(public_path($upload->converted_file)) : null;
                $processedDocument->delete();
            }
        }

        return config('externallinks.s3_storage_url').$upload->file;
    }
}
