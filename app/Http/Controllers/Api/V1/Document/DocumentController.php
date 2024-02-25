<?php

namespace App\Http\Controllers\Api\V1\Document;

use App\Enums\EntryPoint;
use App\Http\Controllers\Controller;
use App\Http\Requests\Document\StoreDocumentFormRequest;
use App\Http\Requests\Document\UpdateDocumentFormRequest;
use App\Models\Document;
use App\Services\Document\DocumentConvService;
use App\Services\Document\DocumentService;

class DocumentController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/documents",
     *      operationId="allDocuments",
     *      tags={"Documents"},
     *      summary="Create a new Document",
     *      description="Create a new Document",
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
        return $this->showAll((new DocumentService())->userDocumentsInShortDetails());
    }

    /**
     * @OA\Post(
     *      path="/api/v1/documents",
     *      operationId="postDocument",
     *      tags={"Documents"},
     *      summary="Post Documents",
     *      description="Post Documents",
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(ref="#/components/schemas/StoreDocumentFormRequest")
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
    public function store(StoreDocumentFormRequest $request)
    {
        $documentProperty = [
            'user_id' => auth('api')->id(),
            'public' => true,
            'entry_point' => $request['entry_point'] ? $request['entry_point'] : EntryPoint::Docs,
        ];

        $parentDocument = $request['parent_id'] === null
                                ? (new DocumentService())->createDocument(array_merge($documentProperty, $request->except('files')))
                                : Document::find($request['parent_id']);

        $parentDocument->update($request->only('has_reminder'));

        if ($request->has('files') && $parentDocument) {

            $numberOrdering = $parentDocument->fromLastDocumentOrdering() ? $parentDocument->fromLastDocumentOrdering()?->number_ordering : 0;

            foreach ($request['files'] as $file) {

                $property = [
                    'title' => $file['title'],
                    'user_id' => auth('api')->id(),
                    'public' => true,
                    'entry_point' => $request['entry_point'],
                    'has_reminder' => $request['has_reminder'],
                ];

                $document = (new DocumentService())->createDocument(array_merge($property, $request->except('files')));

                $request['parent_id'] ? (new DocumentConvService())->collectAllRequest(['files' => [$file['file']]], $document, null, $numberOrdering) : null;

                $initiateNumberOrdering = $document->documentPagesProcessing ? $document->documentPagesProcessing()->orderBy('number_ordering', 'desc')->first()?->number_ordering : 1;

                $numberOrdering += $initiateNumberOrdering;
            }
        }

        return $this->showOne((new DocumentService())->userDocumentByParentId($parentDocument->id));
    }

    /**
     * @OA\Get(
     *      path="/api/v1/documents/{id}",
     *      operationId="showDocuments",
     *      tags={"Documents"},
     *      summary="Show Documents",
     *      description="Show Documents",
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="Documents ID",
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
        (new DocumentService())->processDocument($id);

        return (new DocumentService())->userDocumentByParentId($id)
            ? $this->showOne((new DocumentService())->userDocumentByParentId($id))
            : $this->errorResponse('Document does not exist', 409);
    }

    /**
     * @OA\Put(
     *      path="/api/v1/documents/{id}",
     *      operationId="updateDocuments",
     *      tags={"Documents"},
     *      summary="Replace Documents",
     *      description="Replace Documents",
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="Documents ID",
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
     *          @OA\JsonContent(ref="#/components/schemas/UpdateDocumentFormRequest")
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

        $document->update($request->only('has_reminder'));

        $document->update($request->validated());

        // if (! empty($document->tools)) {
        //     foreach ($document->tools as $tools) {
        //         $tools->forceDelete();
        //     }
        // }

        // if (! empty($document->uploads)) {
        //     foreach ($document->uploads as $upload) {
        //         $upload->file_url ? Storage::disk('s3')->delete($upload->file_url) : null;
        //         $upload->forceDelete();
        //     }
        // }

        // $request['files'] ? (new DocumentConversionService())->collectAllRequest($request->only('files'), $document) : null;

        return $this->showOne((new DocumentService())->userDocumentById($id));
    }

    /**
     * @OA\Delete(
     *      path="/api/v1/document/{id}",
     *      operationId="deleteDocument",
     *      tags={"Documents"},
     *      summary="Delete Document",
     *      description="Delete Document",
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="Document ID",
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

        $document->delete();

        return $document->delete()
            ? $this->showAll((new DocumentService())->userDocuments())
            : $this->errorResponse('cannot delete document participant', 404);
    }
}
