<?php

namespace App\Models;

use App\Http\Resources\Schedule\ScheduleSessionCollection;
use App\Http\Resources\Schedule\ScheduleSessionResource;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleSession extends Model
{
    use HasFactory, HasUuids;

    public $oneItem = ScheduleSessionResource::class;

    public $allItems = ScheduleSessionCollection::class;

    public $guarded = [];

    public function document()
    {
        return $this->hasOne(Document::class, 'id');
    }

    public function servicePlan()
    {
        return $this->hasOne(ServicePlan::class);
    }

    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }

    public function paidTransactions()
    {
        return $this->morphMany(Transaction::class, 'transactionable')->whereNotNull('payment_reference')->where('status', 'Paid');
    }

    public function pendingTransactions()
    {
        return $this->morphMany(Transaction::class, 'transactionable')->where('status', 'Pending');
    }

    public function complianceQuestions()
    {
        return $this->morphMany(ComplianceQuestion::class, 'compliance');
    }

    public function schedule()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function notary()
    {
        return $this->belongsTo(User::class, 'notary_id');
    }

    public function childSessions()
    {
        return $this->belongsTo(ScheduleSession::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(ScheduleSession::class, 'parent_id');
    }

    public function scheduleSessionRequests()
    {
        return $this->hasMany(ScheduleSessionRequest::class, 'scheduled_session_id');
    }

    public function scheduleSessionRequestResponse()
    {
        return $this->hasOne(ScheduleSessionRequest::class, 'scheduled_session_id')->where('notary_id', auth()->user()->id);
    }

    public function scheduleSessionRecordings()
    {
        return $this->hasMany(ScheduleSessionRecording::class, 'schedule_session_id');
    }
}
