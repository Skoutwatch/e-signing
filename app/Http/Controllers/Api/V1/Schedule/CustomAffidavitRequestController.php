<?php

namespace App\Http\Controllers\Api\V1\Schedule;

use App\Http\Controllers\Controller;
use App\Http\Requests\Schedule\UpdateCustomAffidavitRequestFormRequest;
use App\Models\Document;
use App\Models\ScheduleSession;
use App\Services\Document\DocumentConvService;
use Illuminate\Support\Facades\Storage;

class CustomAffidavitRequestController extends Controller
{
    /**
     * @OA\Put(
     *      path="/api/v1/custom-affidavit-request/{id}",
     *      operationId="updateCustomAffidavitRequest",
     *      tags={"Schedule"},
     *      summary="Update CustomAffidavitRequest",
     *      description="Update CustomAffidavitRequest",
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="CustomAffidavitRequest ID",
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
     *          @OA\JsonContent(ref="#/components/schemas/UpdateCustomAffidavitRequestFormRequest")
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
    public function update(UpdateCustomAffidavitRequestFormRequest $request, $id)
    {
        $session = ScheduleSession::find($id);

        $document = Document::find($session->schedule_id);

        $request['title'] ? $document->update($request->only('title')) : null;

        if (! empty($document->tools)) {
            foreach ($document->tools as $tools) {
                $tools->forceDelete();
            }
        }

        if (! empty($document->uploads)) {
            foreach ($document->uploads as $upload) {
                $upload->file_url ? Storage::disk('s3')->delete($upload->file_url) : null;
                $upload->forceDelete();
            }
        }

        // $request['files'] ? (new DocumentConversionService())->collectAllRequest($request->only('files'), $document) : null;

        $request['files'] ? (new DocumentConvService())->collectAllRequest($request->only('files'), $document) : null;

        return $this->showOne($session);
    }
}
