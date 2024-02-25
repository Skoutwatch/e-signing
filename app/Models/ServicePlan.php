<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicePlan extends Model
{
    use HasFactory, HasUuids;

    public function scheduledSession()
    {
        return $this->belongsTo(ScheduleSession::class);
    }
}
