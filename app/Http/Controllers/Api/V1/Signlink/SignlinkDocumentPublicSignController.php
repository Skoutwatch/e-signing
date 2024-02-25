<?php

namespace App\Http\Controllers\Api\V1\Signlink;

use App\Http\Controllers\Controller;
use App\Http\Resources\Signlink\SignlinkDocumentResource;
use App\Models\Document;

class SignlinkDocumentPublicSignController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/signlink-public-sign/{id}",
     *      operationId="showPublicSignlinkDocuments",
     *      tags={"SignlinkDocuments"},
     *      summary="Show SignlinkDocuments",
     *      description="Show SignlinkDocuments",
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="SignlinkDocuments ID",
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
     *      security={ {"bearerAuth": {}} },
     * )
     */
    public function show($id)
    {
        $document = Document::find($id);

        if ($document->active == false) {
            return $this->errorResponse('This link is not available for you to sign at this moment', 409);
        }

        $replicatedDocument = [
            'title' => $document['title'],
            'is_a_signlink_docs' => true,
            'public' => true,
            'parent_id' => $document['id'],
            'status' => 'Processing',
        ];

        $newDocument = Document::create($replicatedDocument);

        $documentUploads = $document->uploads->whereNull('parent_id');

        foreach ($documentUploads as $documentUpload) {
            $newDocumentUpload = $newDocument->uploads()->create([
                'file' => $documentUpload['file'],
                'file_url' => $documentUpload['file_url'],
                'number_ordering' => $documentUpload['number_ordering'],
                'type' => $documentUpload['type'],
                'base64_type' => $documentUpload['base64_type'],
                'status' => 'Processing',
            ]);

            foreach ($documentUpload->tools as $tool) {
                $newDocumentUpload->tools()->create([
                    'document_id' => $newDocument->id,
                    'document_upload_id' => $newDocumentUpload->id,
                    'tool_name' => $tool['tool_name'],
                    'user_id' => $tool['user_id'],
                    'tool_class' => $tool['tool_class'],
                    'tool_width' => $tool['tool_width'],
                    'tool_height' => $tool['tool_height'],
                    'tool_pos_top' => $tool['tool_pos_top'],
                    'tool_pos_left' => $tool['tool_pos_left'],
                    'value' => $tool['value'],
                    'allow_signature' => $tool['allow_signature'],
                ]);
            }
        }

        return new SignlinkDocumentResource(Document::with('uploads', 'signlinkTools')->find($newDocument->id));
    }
}
