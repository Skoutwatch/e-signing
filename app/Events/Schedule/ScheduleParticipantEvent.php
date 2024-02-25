<?php

namespace App\Events\Schedule;

use App\Models\Document;
use App\Models\ScheduleSession;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ScheduleParticipantEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $document;

    public $schedule;

    public function __construct(Document $document, ScheduleSession $schedule)
    {
        $this->document = $document;
        $this->schedule = $schedule;
    }
}
