<?php

namespace App\Http\Controllers\Api\V1\Document;

use App\Enums\DocumentParticipantStatus;
use App\Enums\DocumentStatus;
use App\Enums\EntryPoint;
use App\Enums\ScheduleSessionStatus;
use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DocumentLocker;
use App\Models\DocumentParticipant;
use App\Models\DocumentResourceTool;
use App\Models\DocumentUpload;
use App\Models\ScheduleSession;
use App\Services\Document\DocumentService;

class DocumentStatisticsController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/document-statistics",
     *      operationId="documentStatistics",
     *      tags={"Documents"},
     *      summary="documentStatistics",
     *      description="documentStatistics",
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

        $user = auth('api')->user();

        $ownerDocumentIds = (new DocumentService())->userDocumentsInShortDetails()->pluck('id')->toArray();

        $userSignedPrints = $user->tools->whereNotNull('append_print_id')->pluck('document_upload_id')->toArray();

        $documentIds = DocumentUpload::whereIn('id', $userSignedPrints)->pluck('document_id')->toArray();

        $documentPropertyIds = array_unique(array_merge($ownerDocumentIds, $documentIds));

        $signedNotes = Document::whereIn('id', $documentPropertyIds)->where('Status', 'Completed')->whereDoesntHave('locker')->latest()->get();

        $userSignedPrints = $user->tools->whereNotNull('append_print_id')->pluck('document_upload_id')->toArray();

        $documentUploadIds = DocumentUpload::whereIn('id', $userSignedPrints)->pluck('document_id')->toArray();

        $signedNotes = Document::whereIn('id', $documentUploadIds)->whereDoesntHave('locker')->latest()->get();

        $unsignedNotes = DocumentResourceTool::where('user_id', auth('api')->id())->where('signed', false)->groupBy('document_id')->get();

        $received = DocumentParticipant::where('user_id', auth('api')->id())->where('who_added_id', '!=', auth('api')->id())->pluck('document_id')->toArray();

        $locker = DocumentLocker::where('user_id', auth('api')->id())->get()->pluck('document_id')->toArray();

        $arrayDifference = array_diff($received, $locker);

        $receivedDocuments = Document::whereIn('id', $arrayDifference)->whereDoesntHave('locker')->latest()->get();

        $userTeam = $user->activeTeam->team;

        $videoSessionCount = ScheduleSession::where('entry_point', EntryPoint::Video)->where('customer_id', auth('api')->id())->count();

        $affidavitsSessionCount = ScheduleSession::where('entry_point', EntryPoint::Affidavit)->where('customer_id', auth('api')->id())->count();

        $notarySessionCount = ScheduleSession::where('entry_point', EntryPoint::Notary)->where('customer_id', auth('api')->id())->count();

        $declinedDocuments = DocumentParticipant::where('status', DocumentParticipantStatus::Declined)->where('user_id', auth('api')->id())->select('user_id')->distinct()->get()->count();

        return [
            'draft' => $userTeam->newDocuments->count(),
            'received' => $receivedDocuments->count(),
            'deleted' => $userTeam->deletedDocuments->count(),
            'completed' => $userTeam->documents->where('status', DocumentStatus::Completed)->count(),
            'sent' => $userTeam->envelopsSent->where('status', DocumentStatus::Sent)->count(),
            'notary_request' => $user->userScheduledSessions->whereIn('status', ['Awaiting', 'Completed', 'Paid'])->count(),
            'complete_sessions' => $user->userScheduledSessions->where('status', ScheduleSessionStatus::Completed)->count(),
            'signed_notes' => ($signedNotes)->count(),
            'unsigned_notes' => $unsignedNotes->count(),
            'received_notes' => $receivedDocuments->count(),
            'completed_notes' => $userTeam->documents()->where('status', DocumentStatus::Completed)->whereDoesntHave('locker')->get()->count(),
            'notarySessionCount' => $notarySessionCount,
            'affidavitsSessionCount' => $affidavitsSessionCount,
            'videoSessionCount' => $videoSessionCount,
            'declinedDocumentCount' => $declinedDocuments,
        ];
    }
}
