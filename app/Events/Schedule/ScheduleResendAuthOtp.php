<?php

namespace App\Events\Schedule;

use App\Models\DocumentParticipant;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ScheduleResendAuthOtp
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $participant;

    public function __construct(DocumentParticipant $participant)
    {
        $this->participant = $participant;
    }
}
