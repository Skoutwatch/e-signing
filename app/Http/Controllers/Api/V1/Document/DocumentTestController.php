<?php

namespace App\Http\Controllers\Api\V1\Document;

use App\Http\Controllers\Controller;
use App\Http\Requests\Document\StoreDocumentFormRequest;
use App\Http\Requests\Document\UpdateDocumentFormRequest;
use App\Models\Document;
use App\Models\User;
use App\Services\Document\DocumentConversionService;
use App\Services\Document\DocumentService;
use App\Services\Subscription\SubscriptionRestrictionService;
use Illuminate\Support\Facades\Storage;

class DocumentTestController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/documents-test",
     *      operationId="allDocumentTest",
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
        return User::where('email', 'sakinropo@gmail.com')->first()->activeTeam?->team?->subscription;
    }

    /**
     * @OA\Post(
     *      path="/api/v1/documents-test",
     *      operationId="postDocumentTest",
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
        (new SubscriptionRestrictionService())->checkRestrictions('Number of Envelops', 'envelops');

        $documentProperty = [
            'user_id' => auth('api')->id(),
            'title' => $request['title'],
            'public' => true,
        ];

        $document = (new DocumentService())->createDocument($documentProperty);

        $request['files'] ? (new DocumentConversionService())->collectAllRequest($request->only('files'), $document) : null;

        return $this->showOne((new DocumentService())->userDocumentById($document->id));
    }

    /**
     * @OA\Get(
     *      path="/api/v1/documents-test/{id}",
     *      operationId="showDocumentsTest",
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
        return (new DocumentService())->userDocumentById($id)
            ? $this->showOne((new DocumentService())->userDocumentById($id))
            : $this->errorResponse('Document does not exist', 409);
    }

    /**
     * @OA\Put(
     *      path="/api/v1/documents-test/{id}",
     *      operationId="updateDocumentsTest",
     *      tags={"Documents"},
     *      summary="Update Documents",
     *      description="Update Documents",
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
        $request['title'] ? Document::find($id)->update($request->only('title')) : null;

        return $this->showOne((new DocumentService())->userDocumentById($id));
    }

    /**
     * @OA\Delete(
     *      path="/api/v1/document-test/{id}",
     *      operationId="deleteDocumentTest",
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
            ? $this->showAll((new DocumentService())->userDocuments())
            : $this->errorResponse('cannot delete document participant', 404);
    }

    public function checkUrln($url)
    {
        $headers = get_headers($url);

        return stripos($headers[0], '200 OK') ? true : false;
    }
}
