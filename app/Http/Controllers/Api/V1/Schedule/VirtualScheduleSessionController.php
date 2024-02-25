<?php

namespace App\Http\Controllers\Api\V1\Schedule;

use App\Enums\ScheduleSessionStatus;
use App\Enums\ScheduleSessionType;
use App\Events\Schedule\Notary\AcceptOrRejectCustomerEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Schedule\StoreVitrualScheduleSessionFormRequest;
use App\Http\Requests\Schedule\UpdateVirtualScheduleSessionFormRequest;
use App\Models\Document;
use App\Models\DocumentParticipant;
use App\Models\DocumentTemplate;
use App\Models\ScheduleSession;
use App\Models\User;
use App\Services\Document\DocumentConvService;
use App\Services\Document\DocumentParticipantService;
use App\Services\Document\DocumentService;
use App\Services\ScheduleSession\ScheduleSessionCheckMonetaryValueService;
use App\Services\User\UserService;
use Illuminate\Database\Eloquent\Builder;

class VirtualScheduleSessionController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/request-virtual-session",
     *      operationId="allVirtualScheduledRequest",
     *      tags={"Schedule"},
     *      summary="Get all Scheduled request",
     *      description="get Scheduled request",
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
        $search = request()->get('search') ? request()->get('search') : null;

        $documentParticipantInAVideoSession = DocumentParticipant::where('user_id', auth()->id())->latest()
            ->pluck('document_id')
            ->toArray();

        $requests = ScheduleSession::with(
            'schedule',
            'schedule.seals',
            'transactions',
            'user',
            'notary',
            'scheduleSessionRecordings',
        )->whereIn('schedule_id', $documentParticipantInAVideoSession)
            ->where('schedule_type', 'Document')
            ->when($search, function (Builder $builder, $search) {
                $builder->where('schedule_sessions.title', 'LIKE', "%{$search}%");
            })
            ->latest()
            ->get();

        return $this->showAll($requests);
    }

    /**
     * @OA\Post(
     *      path="/api/v1/request-virtual-session",
     *      operationId="postVirtualSession",
     *      tags={"Schedule"},
     *      summary="Post postVirtualSession",
     *      description="Post postVirtualSession",
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(ref="#/components/schemas/StoreVitrualScheduleSessionFormRequest")
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
    public function store(StoreVitrualScheduleSessionFormRequest $request)
    {
        $parentDocument = Document::find($request['parent_id']);

        $documentProperty = [
            'user_id' => auth('api')->id(),
            'parent_id' => $parentDocument->id,
            'title' => $request['title'],
            'public' => true,
            'entry_point' => $request['entry_point'],
        ];

        $parentDocument = match ($request['request_type']) {
            'Document' => $parentDocument,
            'Custom', 'Upload' => $this->getParentFromAfterCreatingChildDocument($request, $documentProperty, $parentDocument),
            'Template' => $this->getDocumentFromDocumentTemplate($request, $parentDocument),
        };

        foreach ($request['participants'] as $participant) {
            $user = (new UserService())->createOrFindUserIfExist($participant, 'documents');

            $documentParticipant = $parentDocument->participants->where('user_id', $user->id)->first();

            ($documentParticipant === null) ? (new DocumentParticipantService())->addParticipant($parentDocument, $participant, $user) : null;
        }

        $schedule = auth('api')->user()->userScheduledSessions()->create(
            array_merge(
                $request->only(
                    'recipient_name', 'has_monetary_value', 'recipient_email', 'recipient_contact', 'title', 'entry_point', 'description', 'request_type', 'delivery_channel', 'date', 'set_reminder_in_minutes', 'start_time', 'end_time', 'immediate', 'session_type'),
                [

                    'status' => ScheduleSessionStatus::Pending,
                    'schedule_id' => $parentDocument->id,
                    'schedule_type' => class_basename($parentDocument),
                    'type' => ScheduleSessionType::RequestVirtualNotarySession,
                    'notary_id' => $request['notary_id'] != null ? $request['notary_id'] : null,
                    'compliance_required' => $request['notary_id'] != null ? true : false,
                ],
            )
        );

        $team = auth('api')->user()->activeTeam->team;

        $actor_type = $request['actor_type'] == 'User' ? $request['actor_type']
                            : ($request['actor_type'] == class_basename($team) ? $request['actor_type'] : null);

        $actor_id = $request['actor_type'] == 'User' ? auth('api')->id()
                            : ($request['actor_type'] == class_basename($team) ? $team->id : null);

        ($schedule->notary_id) ? $schedule->scheduleSessionRequests()->create(['notary_id' => $request['notary_id']]) : null;

        $notaryParticipant = ($schedule->notary_id) ? ['role' => 'Notary', 'entry_point' => $request['entry_point']] : null;

        $notary = ($schedule->notary_id) ? User::find($schedule->notary_id) : null;

        $notary ? (new DocumentParticipantService())->addParticipant($parentDocument, $notaryParticipant, $notary) : null;

        $notary ? (new AcceptOrRejectCustomerEvent($schedule)) : null;

        $documentTotal = (new ScheduleSessionCheckMonetaryValueService())->verifyMonetaryValueStatusAmount($schedule);

        $schedule->transactions()->create([
            'title' => 'Virtual Notary Session Request Payment',
            'actor_id' => $actor_id,
            'actor_type' => $actor_type,
            'user_id' => auth('api')->id(),
            'subtotal' => $documentTotal,
            'total' => $documentTotal,
            'platform_initiated' => $request['platform_initiated'],
        ]);

        $session = ScheduleSession::with(
            'user',
            'notary',
            'transactions',
            'schedule',
            'schedule.uploads',
            'schedule.participants')->find($schedule->id);

        return $this->showOne($session);
    }

    public function getDocumentFromDocumentTemplate($request, Document $parentDocument)
    {
        $template = DocumentTemplate::find($request['document_template_id']);

        $documentProperty = [
            'user_id' => auth('api')->id(),
            'title' => $template->title,
            'entry_point' => $request['entry_point'],
            'parent_id' => $parentDocument->id,
        ];

        $document = (new DocumentService())->createDocument($documentProperty);

        (new DocumentConvService())->convertUrlToFile($template->file, $template->name, $document);

        return $parentDocument;
    }

    public function getParentFromAfterCreatingChildDocument($request, $documentProperty, Document $parentDocument)
    {
        if ($request->has('files') && $parentDocument) {

            $numberOrdering = 0;

            foreach ($request['files'] as $file) {

                $property = [
                    'title' => $file['title'],
                    'user_id' => auth('api')->id(),
                    'public' => true,
                    'entry_point' => $request['entry_point'],
                    'parent_id' => $parentDocument->id,
                ];

                $document = (new DocumentService())->createDocument(array_merge($property));

                $request['parent_id'] ? (new DocumentConvService())->collectAllRequest(['files' => [$file['file']]], $document, null, $numberOrdering) : null;

                $initiateNumberOrdering = $document->documentPagesProcessing ? $document->documentPagesProcessing()->orderBy('number_ordering', 'desc')->first()?->number_ordering : 1;

                $numberOrdering += $initiateNumberOrdering;
            }
        }

        return $parentDocument;
    }

    /**
     * @OA\Get(
     *      path="/api/v1/request-virtual-session/{id}",
     *      operationId="showVirtualSession",
     *      tags={"Schedule"},
     *      summary="Show VirtualSession",
     *      description="Show VirtualSession",
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="VirtualSession ID",
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
        $session = ScheduleSession::with(
            'user',
            'notary',
            'transactions',
            'schedule',
            'schedule.uploads',
            'schedule.participants'
        )->find($id);

        return $this->showOne($session);
    }

    /**
     * @OA\Put(
     *      path="/api/v1/request-virtual-session/{id}",
     *      operationId="updateVirtualSession",
     *      tags={"Schedule"},
     *      summary="Update VirtualSession",
     *      description="Update VirtualSession",
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="VirtualSession ID",
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
     *          @OA\JsonContent(ref="#/components/schemas/UpdateVirtualScheduleSessionFormRequest")
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
    public function update(UpdateVirtualScheduleSessionFormRequest $request, $id)
    {
        $session = ScheduleSession::find($id);

        $session ? $session->update($request->validated()) : null;

        return $this->showOne(ScheduleSession::find($id));
    }
}
