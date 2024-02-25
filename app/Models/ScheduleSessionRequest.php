<?php

namespace App\Models;

use App\Http\Resources\Schedule\ScheduleSessionRequestCollection;
use App\Http\Resources\Schedule\ScheduleSessionRequestResource;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleSessionRequest extends Model
{
    use HasFactory, HasUuids;

    public $oneItem = ScheduleSessionRequestResource::class;

    public $allItems = ScheduleSessionRequestCollection::class;

    public $guarded = [];

    public function scheduledSession()
    {
        return $this->belongsTo(ScheduleSession::class, 'scheduled_session_id');
    }

    public function notary()
    {
        return $this->belongsTo(User::class);
    }
}
