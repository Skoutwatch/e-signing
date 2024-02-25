<?php

namespace App\Events\Schedule;

use App\Models\Document;
use App\Models\DocumentParticipant;
use App\Models\ScheduleSession;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ScheduleParticipantWhileOnCallEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Document $document, public ScheduleSession $schedule, public DocumentParticipant $participant)
    {
    }
}
