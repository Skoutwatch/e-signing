<?php

namespace App\Http\Controllers\Api\V1\Schedule;

use App\Enums\ScheduleSessionType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Schedule\StoreRequestAffidavitFormRequest;
use App\Models\DocumentTemplate;
use App\Services\Document\DocumentConversionService;
use App\Services\Document\DocumentService;
use App\Services\Pricing\PricingService;

class RequestAffidavitController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/v1/request-affidavits",
     *      operationId="postRequestAffidavits",
     *      tags={"Schedule"},
     *      summary="Post RequestAffidavits",
     *      description="Post RequestAffidavits",
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(ref="#/components/schemas/StoreRequestAffidavitFormRequest")
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
    public function store(StoreRequestAffidavitFormRequest $request)
    {
        $template = $request['schedule_id'] ? DocumentTemplate::find($request['schedule_id']) : null;

        $documentProperty = [
            'user_id' => auth('api')->id(),
            'title' => $request['title'],
            'public' => false,
            'entry_point' => $request['entry_point'],
        ];

        $document = match ($request['type']) {
            'Custom' => (new DocumentService())->createDocument($documentProperty),
            'Template' => (new DocumentService())->createDocument($documentProperty),
        };

        $template ? (new DocumentConversionService())->convertUrlToFile($template->file, $template->name, $document) : null;

        $schedule = auth('api')->user()->userScheduledSessions()->create(
            array_merge(
                $request->except('platform_initiated', 'schedule_id', 'schedule_type'),
                [
                    'session_type' => 'affidavit',
                    'status' => 'New',
                    'schedule_id' => $document->id,
                    'schedule_type' => class_basename($document),
                    'type' => ScheduleSessionType::RequestAffidavit,
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

        $transaction = $schedule->transactions()->create([
            'title' => "$document->title - Affidavits Request Payment",
            'actor_id' => $actor_id,
            'actor_type' => $actor_type,
            'user_id' => auth('api')->id(),
            'subtotal' => $total,
            'total' => $total,
            'platform_initiated' => $request['platform_initiated'],
        ]);

        return $this->showOne($transaction);
    }
}
