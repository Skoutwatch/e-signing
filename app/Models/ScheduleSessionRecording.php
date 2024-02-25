<?php

namespace App\Models;

use App\Http\Resources\Schedule\ScheduleSessionRecordingCollection;
use App\Http\Resources\Schedule\ScheduleSessionRecordingResource;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleSessionRecording extends Model
{
    use HasFactory, HasUuids;

    public $oneItem = ScheduleSessionRecordingResource::class;

    public $allItems = ScheduleSessionRecordingCollection::class;

    public function scheduleSession()
    {
        return $this->belongsTo(ScheduleSession::class);
    }
}
