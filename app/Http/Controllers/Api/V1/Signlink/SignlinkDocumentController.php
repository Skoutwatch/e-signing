<?php

namespace App\Http\Controllers\Api\V1\Signlink;

use App\Http\Controllers\Controller;
use App\Http\Requests\Document\UpdateDocumentFormRequest;
use App\Http\Requests\Signlink\StoreSignlinkDocumentsFormRequest;
use App\Http\Resources\Signlink\SignlinkDocumentResource;
use App\Models\Document;
use App\Services\Document\DocumentConvService;
use App\Services\Document\DocumentService;
use Illuminate\Support\Facades\Storage;

class SignlinkDocumentController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/signlink-documents",
     *      operationId="allSignlinkDocuments",
     *      tags={"SignlinkDocuments"},
     *      summary="Create a new Signlink Document",
     *      description="Create a new Signlink Document",
     *
     *      @OA\Response(
     *          response=200,
     *          description="Successful signin",
     *
     *          @OA\MediaType(
     *             mediaType="application/json",
     *          ),
     *       ),
     *
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      security={ {"bearerAuth": {}} },
     * )
     */
    public function index()
    {
        return SignlinkDocumentResource::collection((new DocumentService())->userSignlinkInShortDetails());
    }

    /**
     * @OA\Post(
     *      path="/api/v1/signlink-documents",
     *      operationId="postSignlinkDocuments",
     *      tags={"SignlinkDocuments"},
     *      summary="Post SignlinkDocuments",
     *      description="Post SignlinkDocuments",
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(ref="#/components/schemas/StoreSignlinkDocumentsFormRequest")
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
    public function store(StoreSignlinkDocumentsFormRequest $request)
    {
        $documentProperty = [
            'user_id' => auth('api')->id(),
            'title' => $request['title'],
            'is_a_signlink_docs' => true,
            'public' => true,
            'entry_point' => 'Docs',
        ];

        $document = (new DocumentService())->createDocument($documentProperty);

        $request['files'] ? (new DocumentConvService())->collectAllRequest($request->only('files'), $document) : null;

        return new SignlinkDocumentResource(Document::with('uploads')->withCount('tools', 'uploads')->find($document->id));
    }

    /**
     * @OA\Get(
     *      path="/api/v1/signlink-documents/{id}",
     *      operationId="showSignlinkDocuments",
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
        return new SignlinkDocumentResource(Document::with('uploads')->withCount('tools', 'uploads')->find($id));
    }

    /**
     * @OA\Put(
     *      path="/api/v1/signlink-documents/{id}",
     *      operationId="updateSignlinkDocuments",
     *      tags={"SignlinkDocuments"},
     *      summary="Update SignlinkDocuments",
     *      description="Update SignlinkDocuments",
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
     *     ),
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(ref="#/components/schemas/UpdateSignlinkDocumentsFormRequest")
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
    public function update(UpdateDocumentFormRequest $request, $id)
    {
        $document = Document::find($id);
        $document->active = $request['active'];
        $document->save();

        return new SignlinkDocumentResource(Document::with('uploads')->withCount('tools', 'uploads')->find($id));
    }

    /**
     * @OA\Delete(
     *      path="/api/v1/signlink-documents/{id}",
     *      operationId="deleteSignlinkDocuments",
     *      tags={"SignlinkDocuments"},
     *      summary="Delete SignlinkDocuments",
     *      description="Delete SignlinkDocuments",
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
    public function destroy($id)
    {
        $document = Document::find($id);

        if (! empty($document->tools)) {
            foreach ($document->tools as $tools) {
                $tools->delete();
            }
        }

        if (! empty($document->uploads)) {
            foreach ($document->uploads as $upload) {
                $upload->file_url ?? Storage::disk('s3')->delete($upload->file_url);
                $upload->delete();
            }
        }

        if (! empty($document->participants)) {
            foreach ($document->participants as $participant) {
                $participant->delete();
            }
        }

        $document->delete();

        return $document->delete()
            ? SignlinkDocumentResource::collection((new DocumentService())->userDocuments())
            : $this->errorResponse('cannot delete document participant', 404);
    }
}
