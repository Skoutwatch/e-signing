<?php

namespace App\Http\Controllers\Api\V1\Schedule;

use App\Enums\ScheduleSessionType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Schedule\StoreDocumentNotaryUploadFormRequest;
use App\Services\Document\DocumentConversionService;
use App\Services\Document\DocumentService;
use App\Services\Pricing\PricingService;

class DocumentNotaryUploadRequest extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/v1/request-affidavits-upload",
     *      operationId="postRequestAffidavitsUpload",
     *      tags={"Schedule"},
     *      summary="Post RequestAffidavitsUpload",
     *      description="Post RequestAffidavitsUpload",
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(ref="#/components/schemas/StoreDocumentNotaryUploadFormRequest")
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
    public function store(StoreDocumentNotaryUploadFormRequest $request)
    {
        $documentProperty = [
            'user_id' => auth('api')->id(),
            'title' => $request['title'],
            'public' => false,
            'entry_point' => $request['entry_point'],
        ];

        $countFiles = ($request['files']) && ($request['entry_point'] != 'Video') ? count($request['files']) : 1;

        $document = (new DocumentService())->createDocument($documentProperty);

        $files = count($request['files']);

        (new DocumentConversionService())->storeRequestUploadFiles($request, $document, 'Awaiting');

        $schedule = auth('api')->user()->userScheduledSessions()->create(
            array_merge(
                $request->except('platform_initiated', 'files', 'type'),
                [
                    'session_type' => 'notary_session',
                    'schedule_id' => $document->id,
                    'schedule_type' => class_basename($document),
                    // 'type' => 'Upload',
                    'status' => 'New',
                    'type' => ScheduleSessionType::RequestANotary,
                    'request_type' => $request['type'],
                ]
            )
        );

        $team = auth('api')->user()->activeTeam->team;

        $actor_type = $request['actor_type'] == 'User' ? $request['actor_type']
                            : ($request['actor_type'] == class_basename($team) ? $request['actor_type'] : null);

        $actor_id = $request['actor_type'] == 'User' ? auth('api')->id()
                            : ($request['actor_type'] == class_basename($team) ? $team->id : null);

        $plan = ($team->subscription?->plan);

        $total = (new PricingService($schedule, $plan))->planPrice();

        $documentTotal = $countFiles ? ($total * $countFiles) : $total;

        $transaction = $schedule->transactions()->create([
            'title' => $request['title'].' for Affidavits Request Payment',
            'actor_id' => $actor_id,
            'actor_type' => $actor_type,
            'user_id' => auth('api')->id(),
            'subtotal' => $documentTotal,
            'total' => $documentTotal,
            'platform_initiated' => $request['platform_initiated'],
        ]);

        return $this->showOne($transaction);
    }
}
