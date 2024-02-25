<?php

namespace App\Http\Resources\Schedule;

use App\Http\Resources\Document\DocumentResource;
use App\Http\Resources\Transaction\TransactionResource;
use App\Http\Resources\User\UserShortDetailResource;
use App\Models\ScheduleSession;
use App\Services\ScheduleSession\ScheduleSessionExtraSeal;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class ScheduleSessionResource extends JsonResource
{
    public function toArray($request)
    {
        $extraSeal = [];

        if ($this->scheduleSession?->transactions) {
            $extraSeal = (new ScheduleSessionExtraSeal())->extraSealFromDocumentOrSession(ScheduleSession::find($this->id));
        }

        return [
            'id' => $this->id,
            'title' => Str::limit($this->title, 40, '...'),
            'has_monetary_value' => ($this->has_monetary_value == 1 || $this->has_monetary_value == true) ? true : false,
            'schedule_session_request_id' => $this->scheduleSessionRequestResponse,
            'description' => $this->description,
            'request_type' => $this->request_type,
            'immediate' => $this->immediate,
            'entry_point' => $this->entry_point,
            'delivery_channel' => $this->delivery_channel,
            'date' => $this->date,
            'set_reminder_in_minutes' => $this->set_reminder_in_minutes,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'status' => $this->status,
            'session' => $this->session,
            'delivery_address' => $this->delivery_address,
            'delivery_email' => $this->delivery_email,
            'customer' => new UserShortDetailResource($this->whenLoaded('user')),
            'notary' => new UserShortDetailResource($this->whenLoaded('notary')),
            'transactions' => TransactionResource::collection($this->whenLoaded('transactions')),
            'paid_transactions' => TransactionResource::collection($this->whenLoaded('paidTransactions')),
            'pending_transactions' => TransactionResource::collection($this->whenLoaded('pendingTransactions')),
            'transaction_paid' => $this->paidTransactions->sum('total'),
            'transaction_pending' => $this->pendingTransactions->sum('total'),
            'token' => $this->token,
            'video_recording_file' => $this->video_session_link,
            'meeting_link' => $this->meeting_link,
            'start_session' => $this->start_session,
            'end_session' => $this->end_session,
            'comment' => $this->comment,
            'customer_id' => $this->customer_id,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
            'compliance_required' => $this->compliance_required ? true : false,
            'cancel_reason' => $this->cancel_reason,
            'participants_count' => $this->schedule?->participants_count,
            'recipient_name' => $this->recipient_name,
            'recipient_email' => $this->recipient_email,
            'recipient_contact' => $this->recipient_contact,
            'video_recordings' => ScheduleSessionRecordingResource::collection($this->whenLoaded('scheduleSessionRecordings')),

            $this->mergeWhen($this->transactions, $extraSeal),

            $this->mergeWhen($this->schedule_type == 'Document', [
                'document' => new DocumentResource($this->whenLoaded('schedule')),
                'completed_file_request' => $this->schedule?->completedDocument?->file_url ? $this->schedule?->completedDocument?->file_url : $this->schedule?->completedDocument?->status,
            ]),
        ];
    }
}
